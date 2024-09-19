import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import OneHotEncoder, LabelEncoder
from keras.models import Sequential
from keras.layers import Dense

# Load data into a Pandas DataFrame
data = pd.read_csv('fitness_data.csv')

# Drop unnecessary columns
data = data[['Exercise_Types', 'Equipment_Availability', 'Preferred_Workout_Location', 'Preferred_Time', 'Exercise_Duration']]

# Define the assign_workout_plan function
def assign_workout_plan(row):
    if row['Exercise_Types'] == "I don't really exercise":
        return 'No_Workout'
    elif row['Exercise_Types'] == 'Cardio' or row['Exercise_Types'] == 'Walking or jogging' or row['Exercise_Types'] == 'Swimming':
        return 'Cardio_Exercises'
    elif row['Exercise_Types'] == 'Strength' or row['Exercise_Types'] == 'Gym' or row['Exercise_Types'] == 'Lifting weights':
        return 'Gym_Exercises'
    elif row['Exercise_Types'] == 'Yoga':
        return 'Yoga_Exercises'
    elif row['Exercise_Types'] == 'Zumba dance':
        return 'Zumba_Exercises'
    elif row['Exercise_Types'] == 'Team sport':
        return 'Team_Sport_Exercises'
    else:
        return 'General_Fitness'

# Create the 'Workout_Plan' column using the assign_workout_plan function
data['Workout_Plan'] = data.apply(assign_workout_plan, axis=1)

# Encode categorical variables using one-hot encoding
encoder = OneHotEncoder()
categorical_data = encoder.fit_transform(data[['Exercise_Types', 'Equipment_Availability', 'Preferred_Workout_Location', 'Preferred_Time']]).toarray()

# Convert the 'Workout_Plan' column to integer labels
label_encoder = LabelEncoder()
data['Workout_Plan'] = label_encoder.fit_transform(data['Workout_Plan'])

# Concatenate encoded categorical data and the 'Workout_Plan' column
preprocessed_data = np.concatenate([categorical_data, data['Workout_Plan'].values.reshape(-1, 1)], axis=1)

# Split the data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(preprocessed_data, data['Workout_Plan'], test_size=0.2, random_state=42)

# Define the neural network architecture
num_classes = len(label_encoder.classes_)
model = Sequential()
model.add(Dense(64, activation='relu', input_dim=X_train.shape[1]))
model.add(Dense(32, activation='relu'))
model.add(Dense(num_classes, activation='softmax'))

# Compile the model
model.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])

# Train the neural network on the training data and validate it on the testing data
model.fit(X_train, y_train, validation_data=(X_test, y_test), epochs=50, batch_size=32)

# Evaluate the performance of the neural network
model.evaluate(X_test, y_test)

# Save the trained model for integration with your website
model.save('workout_planner2_model.h5')


