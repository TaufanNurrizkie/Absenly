// ══════════════════════════════════════════════════════════
// Absensi Tab Switching & Absen Pulang Logic
// ══════════════════════════════════════════════════════════

let currentTab = "masuk";
let absensiState = {
    hasCheckedIn: false,
    checkInTime: null,
    hasCheckedOut: false,
    checkOutTime: null,
};

let mapPulang, markerPulang, circlePulang;
let faceDetectionIntervalPulang = null;
let isFaceDetectedPulang = false;

// ── Initialize modal saat pertama kali dibuka ──
document.addEventListener("DOMContentLoaded", function () {
    // Fetch status absensi
    fetchAbsensiStatus();

    // Override function startAbsensi yang sudah ada
    const originalStartAbsensi = window.startAbsensi;
    window.startAbsensi = function () {
        originalStartAbsensi();
        // Set default tab ke "masuk" saat modal dibuka
        setTimeout(() => switchTab("masuk"), 100);
    };
});

async function fetchAbsensiStatus() {
    try {
        const response = await fetch("/siswa/absensi-status", {
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                Accept: "application/json",
            },
        });

        if (response.ok) {
            const data = await response.json();

            // Simpan ke state
            absensiState.hasCheckedIn = data.hasCheckedIn || false;
            absensiState.checkInTime = data.checkInTime || null;
            absensiState.hasCheckedOut = data.hasCheckedOut || false;
            absensiState.checkOutTime = data.checkOutTime || null;

            // Update status info di bawah tombol
            if (data.hasCheckedIn) {
                const statusInfo = document.getElementById("absensiStatusInfo");
                const statusText = document.getElementById("absensiStatusText");
                const statusDot = document.getElementById("absensiStatusDot");

                if (statusInfo) {
                    statusInfo.classList.remove("hidden");
                    statusInfo.classList.add("flex");

                    if (data.hasCheckedOut) {
                        statusText.textContent = `Masuk ${data.checkInTime} · Pulang ${data.checkOutTime}`;
                        statusDot.classList.remove("bg-green-400");
                        statusDot.classList.add("bg-purple-400");
                    } else {
                        statusText.textContent = `Sudah absen masuk jam ${data.checkInTime}`;
                    }
                }
            }
        }
    } catch (error) {
        console.error("Error fetching absensi status:", error);
    }
}

async function detectFacePulang() {
    const video = document.getElementById("videoPulang");
    const badge = document.getElementById("faceBadgePulang");
    const statusText = document.getElementById("faceStatusPulang");
    const submitBtn = document.getElementById("submitPulangBtn");

    if (!video || !video.videoWidth) return;
    if (
        typeof faceapi === "undefined" ||
        !faceapi.nets.tinyFaceDetector.isLoaded
    ) {
        statusText.textContent = "Loading AI...";
        return;
    }

    try {
        const detection = await faceapi.detectSingleFace(
            video,
            new faceapi.TinyFaceDetectorOptions({
                inputSize: 224,
                scoreThreshold: 0.5,
            }),
        );

        if (detection) {
            isFaceDetectedPulang = true;
            badge.className =
                "face-badge detected absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-green-500/15 text-green-400 border border-green-500/50";
            statusText.textContent = "✓ Face Detected";
            submitBtn.disabled = false;
            submitBtn.style.opacity = "1";
        } else {
            isFaceDetectedPulang = false;
            badge.className =
                "face-badge not-detected absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-red-500/15 text-red-400 border border-red-500/40";
            statusText.textContent = "✗ No Face";
            submitBtn.disabled = true;
            submitBtn.style.opacity = "0.45";
        }
    } catch (error) {
        console.error("Detection error", error);
    }
}

function initCameraAndMapPulang() {
    const allowedLat = -6.949648486282659;
    const allowedLng = 107.685995;
    const allowedRadius = 10000;

    navigator.geolocation.getCurrentPosition(
        function (position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            // Initialize map
            mapPulang = L.map("mapPulang").setView([lat, lng], 15);
            L.tileLayer(
                "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            ).addTo(mapPulang);
            markerPulang = L.marker([lat, lng])
                .addTo(mapPulang)
                .bindPopup("Your Location")
                .openPopup();
            circlePulang = L.circle([allowedLat, allowedLng], {
                radius: allowedRadius,
                color: "#a855f7",
                fillOpacity: 0.1,
            }).addTo(mapPulang);

            // Initialize camera
            navigator.mediaDevices
                .getUserMedia({
                    video: { facingMode: "user", width: 640, height: 480 },
                })
                .then((stream) => {
                    const video = document.getElementById("videoPulang");
                    video.srcObject = stream;
                    video.onloadedmetadata = () => {
                        video.play();
                        setTimeout(() => {
                            faceDetectionIntervalPulang = setInterval(
                                detectFacePulang,
                                300,
                            );
                            detectFacePulang();
                        }, 500);
                    };
                })
                .catch((err) => {
                    if (typeof Swal !== "undefined") {
                        Swal.fire(
                            "Error",
                            "Could not access camera: " + err.message,
                            "error",
                        );
                    }
                });
        },
        function (error) {
            if (typeof Swal !== "undefined") {
                Swal.fire(
                    "Error",
                    "Location access denied: " + error.message,
                    "error",
                );
            }
        },
    );
}

function cleanupPulangResources() {
    // Stop face detection
    if (faceDetectionIntervalPulang) {
        clearInterval(faceDetectionIntervalPulang);
        faceDetectionIntervalPulang = null;
    }

    // Stop camera
    const video = document.getElementById("videoPulang");
    if (video && video.srcObject) {
        video.srcObject.getTracks().forEach((t) => t.stop());
        video.srcObject = null;
    }

    // Remove map
    if (mapPulang) {
        mapPulang.remove();
        mapPulang = null;
        markerPulang = null;
        circlePulang = null;
    }

    isFaceDetectedPulang = false;
    const badge = document.getElementById("faceBadgePulang");
    if (badge) {
        badge.className =
            "face-badge waiting absolute bottom-3 left-1/2 -translate-x-1/2 px-3.5 py-1.5 rounded-full text-[11px] font-bold tracking-[0.4px] flex items-center gap-1.5 backdrop-blur-[10px] whitespace-nowrap transition-all duration-300 bg-slate-700/85 text-slate-200 border border-slate-400/30";
        document.getElementById("faceStatusPulang").textContent = "Waiting...";
    }
}

function switchTab(tab) {
    currentTab = tab;
    const tabMasukBtn = document.getElementById("tabMasukBtn");
    const tabPulangBtn = document.getElementById("tabPulangBtn");
    const panelMasuk = document.getElementById("panelMasuk");
    const panelPulang = document.getElementById("panelPulang");

    if (tab === "masuk") {
        // Cleanup pulang resources jika sebelumnya ada
        cleanupPulangResources();

        // Style tab
        tabMasukBtn.classList.add("bg-white", "text-slate-800", "shadow-sm");
        tabMasukBtn.classList.remove("text-slate-500");
        tabPulangBtn.classList.remove(
            "bg-white",
            "text-slate-800",
            "shadow-sm",
        );
        tabPulangBtn.classList.add("text-slate-500");

        // Show/hide panel
        panelMasuk.classList.remove("hidden");
        panelPulang.classList.add("hidden");

        // Update title
        document.getElementById("modalTitle").textContent = "Absen Masuk";
        document.getElementById("modalSubtitle").textContent =
            "Scan wajah dan lokasi";

        // Jika sudah absen masuk, tampilkan note
        const masukNote = document.getElementById("masukAlreadyNote");
        const masukTime = document.getElementById("masukAlreadyTime");
        const submitBtn = document.getElementById("submitBtn");

        if (masukNote && masukTime && absensiState.hasCheckedIn) {
            masukNote.classList.remove("hidden");
            masukTime.textContent = absensiState.checkInTime;
            submitBtn.disabled = true;
            submitBtn.style.opacity = "0.45";
        } else if (masukNote) {
            masukNote.classList.add("hidden");
        }
    } else {
        // Style tab
        tabPulangBtn.classList.add("bg-white", "text-slate-800", "shadow-sm");
        tabPulangBtn.classList.remove("text-slate-500");
        tabMasukBtn.classList.remove("bg-white", "text-slate-800", "shadow-sm");
        tabMasukBtn.classList.add("text-slate-500");

        // Show/hide panel
        panelPulang.classList.remove("hidden");
        panelMasuk.classList.add("hidden");

        // Update title
        document.getElementById("modalTitle").textContent = "Absen Pulang";
        document.getElementById("modalSubtitle").textContent =
            "Scan wajah dan lokasi";

        // Update info absen masuk
        document.getElementById("modalCheckInTime").textContent =
            absensiState.checkInTime || "--:--";

        // Logic untuk status notes dan button
        const notYetInNote = document.getElementById("pulangNotYetInNote");
        const notYetTimeNote = document.getElementById("pulangNotYetTimeNote");
        const alreadyNote = document.getElementById("pulangAlreadyNote");
        const cameraSection = document.getElementById("pulangCameraSection");

        // Reset all notes
        notYetInNote.classList.add("hidden");
        notYetTimeNote.classList.add("hidden");
        alreadyNote.classList.add("hidden");
        cameraSection.style.display = "none";

        if (!absensiState.hasCheckedIn) {
            // Belum absen masuk
            notYetInNote.classList.remove("hidden");
        } else if (absensiState.hasCheckedOut) {
            // Sudah absen pulang
            alreadyNote.classList.remove("hidden");
            const checkOutTimeEl = document.getElementById("modalCheckOutTime");
            if (checkOutTimeEl) {
                checkOutTimeEl.textContent = absensiState.checkOutTime;
            }
        } else {
            // Sudah absen masuk tapi belum pulang
            const now = new Date();
            const hour = now.getHours();
            const minute = now.getMinutes();
            const currentTime = hour * 60 + minute;
            const minTime = 1 * 10; // 15:00

            if (currentTime >= minTime) {
                // Boleh absen pulang - tampilkan camera
                cameraSection.style.display = "block";
                initCameraAndMapPulang();
            } else {
                // Belum waktunya
                notYetTimeNote.classList.remove("hidden");
                const hoursLeft = Math.floor((minTime - currentTime) / 60);
                const minsLeft = (minTime - currentTime) % 60;
                notYetTimeNote.textContent = `Absen pulang tersedia dalam ${hoursLeft}j ${minsLeft}m`;
            }
        }
    }
}

async function captureAndSubmitPulang() {
    const video = document.getElementById("videoPulang");
    const canvas = document.getElementById("canvasPulang");

    if (!isFaceDetectedPulang) {
        if (typeof Swal !== "undefined") {
            Swal.fire(
                "Warning",
                "Please position your face correctly.",
                "warning",
            );
        }
        return;
    }

    const allowedLat = -6.949648486282659;
    const allowedLng = 107.685995;
    const allowedRadius = 10000;

    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext("2d").drawImage(video, 0, 0);

    const dataURL = canvas.toDataURL("image/png");
    const latlng = markerPulang.getLatLng();
    const distance = mapPulang.distance(
        [latlng.lat, latlng.lng],
        [allowedLat, allowedLng],
    );

    if (distance > allowedRadius) {
        if (typeof Swal !== "undefined") {
            Swal.fire(
                "Out of Range",
                `You are ${Math.round(distance)}m away from school.`,
                "error",
            );
        }
        return;
    }

    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: "Processing...",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });
    }

    try {
        const response = await fetch("/siswa/absen-pulang", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "Content-Type": "application/json",
                Accept: "application/json",
            },
            body: JSON.stringify({
                photo: dataURL,
                lat: latlng.lat,
                lng: latlng.lng,
            }),
        });

        const data = await response.json();

        if (response.ok && data.status === "success") {
            if (typeof closeModal === "function") {
                closeModal();
            }
            if (typeof Swal !== "undefined") {
                Swal.fire({
                    icon: "success",
                    title: "Berhasil Absen Pulang!",
                    html: `${data.message}<br><span class="text-purple-600 font-semibold">Waktu pulang: ${data.waktu_pulang}</span>`,
                    confirmButtonColor: "#7c3aed",
                }).then(() => location.reload());
            }
        } else {
            throw new Error(data.message || "Gagal absen pulang");
        }
    } catch (error) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                text: error.message || "Terjadi kesalahan saat absen pulang",
                confirmButtonColor: "#7c3aed",
            });
        }
        if (typeof closeModal === "function") {
            closeModal();
        }
    }
}
