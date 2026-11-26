const form = document.getElementById('form-barang');
const inputs = form.querySelectorAll('input');

// Memeriksa satu input dan mengembalikan pesan error
function cekInput(input) {
    const nilai = input.value.trim();

    if (input.id === 'stok' || input.id === 'harga') {
        // Hanya menerima 0 atau bilangan bulat positif
        if (!/^(0|[1-9]\d*)$/.test(nilai)) {
            return 'Wajib diisi dengan bilangan bulat, minimal 0.';
        }
    } else if (nilai === '') {
        return 'Kolom ini wajib diisi.';
    }

    return '';
}

// Menampilkan atau menghapus pesan error
function validasi(input) {
    const pesan = cekInput(input);
    document.getElementById('error-' + input.id).textContent = pesan;
    input.classList.toggle('input-salah', pesan !== '');
    input.classList.toggle('input-benar', pesan === '');
    return pesan === '';
}

inputs.forEach(function (input) {
    // Buat tempat pesan di bawah setiap input
    const teks = document.createElement('small');
    teks.id = 'error-' + input.id;
    teks.className = 'teks-error';
    input.after(teks);

    // Periksa setiap kali pengguna mengetik
    input.addEventListener('input', function () {
        validasi(input);
    });
});

// Periksa semua input saat tombol Simpan diklik
form.addEventListener('submit', function (event) {
    let semuaBenar = true;

    inputs.forEach(function (input) {
        if (!validasi(input)) {
            semuaBenar = false;
        }
    });

    if (!semuaBenar) {
        event.preventDefault(); // batalkan pengiriman form
    }
});