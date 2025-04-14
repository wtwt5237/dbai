import pandas as pd
import json

# Converting JSON data to a pandas DataFrame
df = pd.read_json('cytof.json')

# Writing DataFrame to a CSV file
df.to_csv("output.csv", index=False)