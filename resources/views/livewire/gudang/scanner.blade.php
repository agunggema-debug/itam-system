<div class="min-h-screen bg-gray-900 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md">
        <h1 class="text-white text-2xl font-bold text-center mb-6">QR Scanner</h1>
        <p class="text-gray-400 text-center mb-4">Arahkan kamera ke QR Code aset</p>

        <div id="scanner-container" class="bg-black rounded-lg overflow-hidden shadow-2xl" style="aspect-ratio: 1/1;">
            <div id="reader" class="w-full h-full"></div>
        </div>

        <div id="scan-feedback" class="mt-4 text-center transition-all duration-300 hidden">
            <div id="feedback-icon" class="text-4xl mb-2"></div>
            <p id="feedback-message" class="text-lg font-semibold"></p>
        </div>

        <div class="mt-6 text-center">
            <button onclick="startScanner()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition-all">
                Mulai Scan
            </button>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        let html5QrCode = null;
        let isScanning = false;

        function playBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.frequency.value = 1200;
                oscillator.type = 'sine';
                gainNode.gain.value = 0.3;
                oscillator.start();
                setTimeout(() => {
                    oscillator.stop();
                    audioCtx.close();
                }, 150);
            } catch(e) {
                console.log('Beep not available');
            }
        }

        function showFeedback(success, message, assetName = '') {
            const feedback = document.getElementById('scan-feedback');
            const icon = document.getElementById('feedback-icon');
            const msg = document.getElementById('feedback-message');
            feedback.classList.remove('hidden');
            if (success) {
                feedback.className = 'mt-4 text-center transition-all duration-300 bg-green-900/50 rounded-lg p-4 border border-green-500';
                icon.innerHTML = '&#10003;';
                icon.className = 'text-4xl mb-2 text-green-400';
                msg.className = 'text-lg font-semibold text-green-300';
                msg.textContent = assetName ? assetName + ' - Verified' : 'Verified';
            } else {
                feedback.className = 'mt-4 text-center transition-all duration-300 bg-red-900/50 rounded-lg p-4 border border-red-500';
                icon.innerHTML = '&#10007;';
                icon.className = 'text-4xl mb-2 text-red-400';
                msg.className = 'text-lg font-semibold text-red-300';
                msg.textContent = message || 'Scan failed';
            }
            setTimeout(() => {
                feedback.classList.add('hidden');
            }, 1500);
        }

        function onScanSuccess(decodedText, decodedResult) {
            if (isScanning) return;
            isScanning = true;

            if (html5QrCode) {
                html5QrCode.pause();
            }

            fetch('/api/v1/scan-asset', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    asset_code: decodedText,
                    current_location: 'Gudang'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    playBeep();
                    showFeedback(true, data.message, data.asset_name);
                } else {
                    showFeedback(false, 'Asset not found');
                }
            })
            .catch(error => {
                showFeedback(false, 'Network error');
                console.error('Error:', error);
            })
            .finally(() => {
                setTimeout(() => {
                    isScanning = false;
                    if (html5QrCode) {
                        html5QrCode.resume();
                    }
                }, 1000);
            });
        }

        function startScanner() {
            const reader = document.getElementById('reader');
            if (html5QrCode) {
                html5QrCode.clear();
            }
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                onScanSuccess
            ).catch(err => {
                showFeedback(false, 'Kamera tidak tersedia');
                console.error(err);
            });
        }

        // Auto-start scanner on page load
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(startScanner, 500);
        });
    </script>
</div>
