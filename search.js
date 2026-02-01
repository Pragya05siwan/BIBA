// Voice Search
let recognition;
if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    recognition.continuous = false;
    recognition.interimResults = false;
    recognition.lang = 'en-US';
    
    document.getElementById('voiceBtn').addEventListener('click', function() {
        recognition.start();
        this.style.color = '#e74c3c';
    });
    
    recognition.onresult = function(event) {
        const transcript = event.results[0][0].transcript;
        document.getElementById('searchInput').value = transcript;
        document.getElementById('voiceBtn').style.color = '#333';
    };
    
    recognition.onerror = function() {
        document.getElementById('voiceBtn').style.color = '#333';
        alert('Voice search not supported or microphone access denied');
    };
}

// Camera Search
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const cameraModal = document.getElementById('cameraModal');
let stream;

document.getElementById('cameraBtn').addEventListener('click', async function() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        cameraModal.style.display = 'flex';
    } catch (err) {
        alert('Camera access denied or not available');
    }
});

document.getElementById('captureBtn').addEventListener('click', function() {
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, 400, 300);
    
    // Simple image analysis - detect dominant colors/objects
    const imageData = context.getImageData(0, 0, 400, 300);
    const colors = analyzeImage(imageData);
    
    // Set search term based on detected colors
    let searchTerm = '';
    if (colors.red > colors.green && colors.red > colors.blue) searchTerm = 'red';
    else if (colors.green > colors.red && colors.green > colors.blue) searchTerm = 'green';
    else if (colors.blue > colors.red && colors.blue > colors.green) searchTerm = 'blue';
    else searchTerm = 'fashion';
    
    document.getElementById('searchInput').value = searchTerm;
    closeCameraModal();
});

document.getElementById('closeCameraBtn').addEventListener('click', closeCameraModal);

function closeCameraModal() {
    if (stream) {
        stream.getTracks().forEach(track => track.stop());
    }
    cameraModal.style.display = 'none';
}

function analyzeImage(imageData) {
    const data = imageData.data;
    let red = 0, green = 0, blue = 0;
    
    for (let i = 0; i < data.length; i += 4) {
        red += data[i];
        green += data[i + 1];
        blue += data[i + 2];
    }
    
    const pixels = data.length / 4;
    return {
        red: red / pixels,
        green: green / pixels,
        blue: blue / pixels
    };
}

function addToCart(productId) {
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + productId
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            alert('Added to cart!');
        }
    });
}

function addToWishlist(productId) {
    fetch('add_to_wishlist.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'product_id=' + productId
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            alert('Added to wishlist!');
        } else if (data === 'already_added') {
            alert('Already in wishlist!');
        }
    });
}