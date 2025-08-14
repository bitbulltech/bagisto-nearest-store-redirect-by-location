<div id="location-popup" style="
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    z-index: 9999;
    display: none;
    font-family: Arial, sans-serif;
    text-align: center;
    max-width: 350px;
">
    <p style="margin: 0 0 10px; font-size: 15px;">
        Allow your location to connect you to the nearest store.
    </p>
    <button id="allow-location" style="
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 4px;
        cursor: pointer;
        margin-right: 5px;
    ">Allow</button>
    <button id="close-location" style="
        background-color: #ccc;
        color: #333;
        border: none;
        padding: 8px 14px;
        border-radius: 4px;
        cursor: pointer;
    ">Close</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const popup = document.getElementById('location-popup');
    popup.style.display = 'block'; // Show popup every time homepage loads

    document.getElementById('allow-location').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(pos) {
                fetch(`/nearest-store?lat=${pos.coords.latitude}&lng=${pos.coords.longitude}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        }
                    });
            });
        }
    });

    document.getElementById('close-location').addEventListener('click', function() {
        popup.style.display = 'none';
    });
});
</script>
