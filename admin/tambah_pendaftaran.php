<?php include 'header.php'; ?>

<!--content-->
<div class="content">
    <div class="container1">
        <div class="box">
            <div class="box-header">
                TAMBAH PENDAFTARAN
            </div>
            <div class="box-body">
                <form action="process.php" method="POST" enctype="multipart/form-data" id="formPendaftaran">
                    <div class="form-group">
                        <label>Tanggal Pendaftaran:</label><br><br>
                        <input type="date" name="waktu_pendaftaran" placeholder="Tanggal Pendaftaran" class="input-control" required min="<?php echo date('Y-m-d'); ?>">
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
                        <select name="tempat_cek" class="input-control" required>
                            <option value="">pilih</option>
                            <option value="Di Rumah">Di Rumah</option>
                            <option value="Di Optik">Di Optik</option>
                        </select>
                    </div><br>
                    <div class="form-group">
                        <label>Keluhan:</label><br><br>
                        <textarea name="keluhan" placeholder="Keluhan" class="input-control" id="keterangan"></textarea>
                    </div><br>
                    <div class="form-group" id="bookedTimes">
                        <label for="jam_kunjugan">Jam Kunjungan:</label><br><br>
                        <select name="jam_kunjugan" class="input-control" required>
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
                        <label>Status:</label><br><br>
                        <select name="status" class="input-control" required>
                            <option value="">pilih</option>
                            <option value="proses">Proses</option>
                            <option value="selesai">Selesai</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div><br>
                    <div class="form-group">
                        <label>Pelanggan:</label><br><br>
                        <select name="pelanggan" class="input-control" required>
                            <option value="">pilih</option>
                            <?php
                            $pengguna = mysqli_query($conn, "SELECT * FROM akun_pelanggan ORDER BY username DESC");
                            while ($p = mysqli_fetch_array($pengguna)) {
                                ?>
                                <option value="<?php echo $p['username'] ?>"><?php echo $p['nama'] ?></option>
                            <?php } ?>
                        </select>
                    </div><br>
                    <button type="button" class="btn" onclick="window.location ='pendaftaran.php'">KEMBALI</button>
                    <button type="submit" name="submit" class="btn">SIMPAN</button>
                </form>
            </div>
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
            options.forEach(function(option) {
                if (option.value === "Di Optik") {
                    option.disabled = false;
                } else if (option.value === "Di Rumah") {
                    option.disabled = true;
                }
            });
        } else if (selectedValue === "UMUM") {
            options.forEach(function(option) {
                option.disabled = false;
            });
        } else {
            options.forEach(function(option) {
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

        updateAvailableTimes(selectedDate);
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
            availableTimes = availableTimes.filter(function(time) {
                var hour = parseInt(time.split(':')[0]);
                return hour > currentHour;
            });
        }

        fetch(`proses_jam.php?date=${selectedDateOnly}`)
            .then(response => response.json())
            .then(data => {
                var bookedTimes = data;
                availableTimes = availableTimes.filter(function(time) {
                    return !bookedTimes.includes(time);
                });

                availableTimes.forEach(function(time) {
                    var option = document.createElement('option');
                    option.value =
                    time;
                    option.textContent = time;
                    jamKunjuganSelect.appendChild(option);
                });
            });
    }
</script>
<?php include 'footer.php'; ?>
