document.addEventListener('submit', event => {
    const form = event.target;

    // Konfirmasi sebelum form dikirim
    if (
        form.dataset.confirm &&
        !window.confirm(form.dataset.confirm)
    ) {
        event.preventDefault();
    }
});


// Mengatur perhitungan ongkos kirim dan total pesanan
const shipping = document.querySelector(
    '[name="shipping_service_id"]'
);

if (shipping) {

    const updateTotal = () => {
        const option = shipping.selectedOptions[0];

        const cost = Number(option?.dataset.cost || 0);

        const subtotal = Number(
            document.querySelector('[data-subtotal]')
                ?.dataset.subtotal || 0
        );

        // Format mata uang Rupiah
        const fmt = value => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2
        }).format(value);

        const costNode = document.querySelector(
            '[data-shipping-total]'
        );

        const totalNode = document.querySelector(
            '[data-order-total]'
        );

        // Menampilkan ongkos kirim
        if (costNode) {
            costNode.textContent = shipping.value
                ? fmt(cost)
                : 'Pilih ekspedisi';
        }

        // Menghitung total pembayaran
        if (totalNode) {
            totalNode.textContent = fmt(subtotal + cost);
        }
    };

    // Memperbarui total ketika ekspedisi berubah
    shipping.addEventListener('change', updateTotal);

    // Memperbarui total saat halaman pertama kali dibuka
    updateTotal();
}
