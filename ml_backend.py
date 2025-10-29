from flask import Flask, request, jsonify
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.ensemble import RandomForestClassifier
import joblib
from transformers import pipeline

# Flask app
app = Flask(__name__)

# Load pre-trained models and vectorizer
report_model = joblib.load("report_classifier_model.pkl")
delay_model = joblib.load("report_delay_model.pkl")  # optional
vectorizer = joblib.load("vectorizer.pkl")

# Load summarizer
summarizer = pipeline("summarization")

# Helper function: classify report
def classify_report(description):
    vec = vectorizer.transform([description])
    category = report_model.predict(vec)[0]
    return category

# Helper function: predict delay
def predict_delay(features):
    return delay_model.predict([features])[0]

# Helper function: summarize
def summarize_report(description):
    summary = summarizer(description, max_length=50, min_length=25, do_sample=False)
    return summary[0]['summary_text']

# Dummy legit/fake detector (can improve with more features)
def check_legit(description):
    if len(description.split()) < 3:
        return "Fake"
    return "Legit"

# API Endpoint
@app.route("/analyze_report", methods=["POST"])
def analyze_report():
    data = request.get_json()
    description = data.get("description", "")
    category = classify_report(description)
    summary = summarize_report(description)
    legit_status = check_legit(description)
    
    # For delay prediction, you can send features like type, location, priority
    # Example: delay_status = predict_delay([category, data.get("location"), ...])
    delay_status = "On-Time"  # placeholder

    return jsonify({
        "category": category,
        "summary": summary,
        "legit_status": legit_status,
        "delay_status": delay_status
    })

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
