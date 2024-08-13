<?php include 'header.php'; ?>

<?php
// Redirect ke halaman login jika pengguna tidak logged in
if (!isset($_SESSION['status_login']) || $_SESSION['status_login'] !== true) {
    header("location: login.php?msg=Harap Login Terlebih Dahulu!");
    exit();
}
?>
<!--content-->
<div class="container-utama">
    <div class="box">
        <div class="box-header">
            RESERVASI PENDAFTARAN PEMERIKSAN MATA
        </div>
        <div class="box-body">
            <?php
            //jika ada variabel msg maka tampilkan parameter msg
            if (isset($_GET['msg'])) {
                echo "<div class='alert alert-error'>" . $_GET['msg'] . "</div>";
            }
            //jika ada variabel msg maka tampilkan parameter msg
            if (isset($_GET['berhasil'])) {
                echo "<div class='alert alert-success'>" . $_GET['berhasil'] . "</div>";
            }
            ?>
            <form action="process.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Tanggal Pendaftaran:</label><br><br>
                    <input type="date" name="waktu_pendaftaran" id="waktu_pendaftaran" placeholder="Tanggal Pendaftaran" class="input-control" required min="<?php echo date('Y-m-d'); ?>">
                </div><br>
                <div class="form-group" id="bookedTimes">
                    <label for="jam_kunjugan">Jam Kunjungan:</label><br><br>
                    <select name="jam_kunjugan" class="input-control" required disabled>
                        <option value="">pilih</option>
                        <option value="08:00">08:00</option>
                        <option value="10:00">10:00</option>
                        <option value="12:00">12:00</option>
                        <option value="14:00">14:00</option>
                        <option value="16:00">16:00</option>
                        <option value="19:00">19:00</option>
                        <option value="21:00">21:00</option>
                    </select>
                </div><br>
                <div class="form-group">
                    <label>Kriteria:</label><br><br>
                    <select name="kriteria" class="input-control" required>
                        <option value="">pilih</option>
                        <option value="BPJS">BPJS</option>
                        <option value="UMUM">UMUM</option>
                    </select>
                </div><br>
                <div class="form-group">
                    <label>Tempat Cek:</label><br><br>
                    <select name="tempat_cek" id="tempat_cek" class="input-control" required disabled>
                        <option value="">pilih</option>
                        <option value="Di Rumah">Di Rumah</option>
                        <option value="Di Optik">Di Optik</option>
                    </select>
                </div><br>
                <div class="form-group">
                    <label>Keluhan:</label><br><br>
                    <textarea name="keluhan" placeholder="Keluhan" class="input-control" id="keterangan"></textarea>
                </div><br>
                <button type="button" class="btn" onclick="resetForm()">CLEAR</button>
                <button type="submit" name="submit" class="btn">SIMPAN</button>
            </form>
        </div>
    </div>
</div>
<script>
    var pendaftaranSelect = document.querySelector('[name="kriteria"]');
    var tempatCekSelect = document.querySelector('[name="tempat_cek"]');
    var waktuPendaftaranInput = document.querySelector('[name="waktu_pendaftaran"]');
    var jamKunjuganSelect = document.querySelector('[name="jam_kunjugan"]');

    pendaftaranSelect.addEventListener('change', function () {
        var selectedValue = pendaftaranSelect.value;
        var options = tempatCekSelect.querySelectorAll('option');

        if (selectedValue === "BPJS") {
            options.forEach(function (option) {
                if (option.value === "Di Optik") {
                    option.disabled = false;
                } else if (option.value === "Di Rumah") {
                    option.disabled = true;
                }
            });
        } else if (selectedValue === "UMUM") {
            options.forEach(function (option) {
                option.disabled = false;
            });
        } else {
            options.forEach(function (option) {
                option.disabled = true;
            });
        }
        tempatCekSelect.disabled = false;
    });

    waktuPendaftaranInput.addEventListener('change', function () {
        var selectedDate = new Date(waktuPendaftaranInput.value);
        var today = new Date();
        var oneWeekAhead = new Date(today);
        oneWeekAhead.setDate(today.getDate() + 7);

        if (selectedDate.getDay() === 0) {
            alert('Pendaftaran tidak tersedia pada hari Minggu.');
            waktuPendaftaranInput.value = '';
            return;
        }

        if (selectedDate > oneWeekAhead) {
            alert('Anda hanya dapat memilih tanggal maksimal 1 minggu ke depan.');
            waktuPendaftaranInput.value = '';
            return;
        }

        // Jika tanggal pendaftaran belum dipilih, nonaktifkan pilihan jam kunjungan
        if (waktuPendaftaranInput.value === '') {
            jamKunjuganSelect.disabled = true;
            // Reset pilihan jam kunjungan
            while (jamKunjuganSelect.options.length > 1) {
                jamKunjuganSelect.remove(1);
            }
            // Set opsi default
            var defaultOption = document.createElement('option');
            defaultOption.text = 'pilih';
            defaultOption.value = '';
            jamKunjuganSelect.add(defaultOption);
        } else {
            jamKunjuganSelect.disabled = false; // Aktifkan pilihan jam kunjungan
            updateAvailableTimes(selectedDate);
        }
    });

    function updateAvailableTimes(selectedDate) {
        var today = new Date();
        var availableTimes = ["08:00", "10:00", "12:00", "14:00", "16:00", "19:00", "21:00"];
        var selectedDateOnly = selectedDate.toISOString().split('T')[0];
        var todayOnly = today.toISOString().split('T')[0];

        while (jamKunjuganSelect.options.length > 1) {
            jamKunjuganSelect.remove(1);
        }

        if (selectedDateOnly === todayOnly) {
            var currentHour = today.getHours();
            availableTimes = availableTimes.filter(function (time) {
                var hour = parseInt(time.split(':')[0]);
                return hour > currentHour;
            });
        }

        fetch(`proses_jam.php?date=${selectedDateOnly}`)
            .then(response => response.json())
            .        then(data => {
            var bookedTimes = data;
            availableTimes = availableTimes.filter(function (time) {
                return !bookedTimes.includes(time);
            });

            availableTimes.forEach(function (time) {
                var option = document.createElement('option');
                option.value = time;
                option.textContent = time;
                jamKunjuganSelect.appendChild(option);
            });
        });
}
function resetForm() {
        // Kosongkan nilai input
        waktuPendaftaranInput.value = '';
        // Nonaktifkan pilihan jam kunjungan
        jamKunjuganSelect.disabled = true;
        // Reset pilihan jam kunjungan
        while (jamKunjuganSelect.options.length > 1) {
            jamKunjuganSelect.remove(1);
        }
        // Set opsi default
        var defaultOption = document.createElement('option');
        defaultOption.text = 'pilih';
        defaultOption.value = '';
        jamKunjuganSelect.add(defaultOption);
        // Kosongkan nilai dari input keluhan
        document.getElementById('keterangan').value = '';
        // Kosongkan nilai dari pilihan kriteria
        pendaftaranSelect.value = '';
        // Nonaktifkan pilihan tempat cek
        tempatCekSelect.disabled = true;
        // Set opsi default untuk pilihan tempat cek
        tempatCekSelect.value = '';
    }
</script>
</script>
<?php include 'footer.php'; ?>
