import pandas as pd
import matplotlib.pyplot as plt
import seaborn as sns
import pickle
import json
import os

# Load user input (assuming this part is unchanged)
input_path = 'user_input.csv'
user_input = pd.read_csv(input_path, header=None)
user_input.columns = ['N', 'P', 'K', 'temperature', 'humidity', 'ph', 'rainfall']

# Load trained Random Forest model (assuming this part is unchanged)
model_path = 'RandomForest.pkl'
with open(model_path, 'rb') as file:
    model = pickle.load(file)

# Make prediction (assuming this part is unchanged)
predicted_crop = model.predict(user_input)[0]

# Visualize data
sns.set_theme(style="whitegrid")
df = pd.read_csv('Crop_recommendation.csv')

plt.figure(figsize=(10, 6))
sns.jointplot(x="rainfall", y="humidity", data=df[(df['temperature'] < 40) & (df['rainfall'] > 40)], height=10, hue="label")
plt.xticks(rotation=90)

# Ensure directory exists
output_directory = "C:/xampp/htdocs/shopping/"
os.makedirs(output_directory, exist_ok=True)

# Save plot
graph_image_path = "graph.png"
plt.savefig(graph_image_path)
plt.close()

# Create output (assuming this part is unchanged)
output = {
    'predicted_crop': predicted_crop,
    'graph_image': graph_image_path,
}

# Output result as JSON (assuming this part is unchanged)
print(json.dumps(output))
