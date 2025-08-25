<?php
include "koneksi.php";
$produk = mysqli_query($conn, "SELECT * FROM produk");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir Alan</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            display: flex;
            margin: 0;
            height: 100vh;
            background-color: #f4f4f4; 
        }

        /* Grid produk */
        .menu { 
            flex: 2; 
            display: grid; 
            grid-template-columns: repeat(4, 1fr); 
            gap: 10px; 
            padding: 15px;
            overflow-y: auto;
            background: #e9e9e9;
        }

        .produk { 
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            text-align: center; 
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .produk img { 
            width: 100%; 
            height: 110px; 
            object-fit: cover; 
        }

        .produk p {
            margin: 5px 0;
            padding: 5px;
            font-size: 14px;
            
        }

        /* Panel kasir */
        .kasir { 
            flex: 1; 
            padding: 20px; 
            background: #fff; 
            border-left: 1px solid #ccc; 
            display: flex;
            flex-direction: column;
        }

        .kasir h2 {
            margin: 0 0 10px 0;
            font-size: 25px;
            text-align: center;
        }

        .bill { 
            flex: 1;
            border: 1px solid #ddd; 
            border-radius: 5px;
            margin-bottom: 10px; 
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        
        .bill-header {
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            background: #fafafa;
        }

        .bill-header select {
            padding: 5px;
            font-size: 14px;
            border: none;
            cursor: pointer;
            outline: none;
            background: #fafafa;
        }

        .bill-items {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            font-size: 14px;
        }

        .bill-items p {
            margin: 4px 0;
            display: flex;
            justify-content: space-between;
        }

        .bill-footer {
            border-top: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            background: #fafafa;
        }

        .bill-footer p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }

        .clear-wrap {
            padding: 10px;
            border-top: 1px solid #ddd;
            background: #fff;
        }

        /* tombol */
        button { 
            padding: 10px 15px; 
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            font-size: 14px;
            border: 1px solid transparent;
        }

        .charge { 
            background-color: #008cffff; 
            color: white;
            width: 100%; 
            font-size: 16px;
            border-radius: none;
        }

        .clear { 
            background-color: #fff; 
            color: #777;
            border: 1px solid #ccc;
            width: 100%;
        }

        .btn-row {
            display: flex;
            gap: 5px;
            margin-bottom: 10px;
        }

        .btn-row button {
            flex: 1;
            background: #fff;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>      

<div class="menu">
    <?php while($row = mysqli_fetch_assoc($produk)) { ?>
        <div class="produk" onclick="tambahProduk('<?php echo $row['nama']; ?>', <?php echo $row['harga']; ?>)">
            <img src="uploads/<?php echo $row['gambar']; ?>" alt="">
            <p><?php echo $row['nama']; ?></p>
        </div>
    <?php } ?>
</div>

<div class="kasir">
    <a href="produk.php"> <button style="background-color: orange; color:white; pading:10px;">+ Tambah Produk</button></a>
    <h2>New Customer</h2>

    <div class="bill">
        <div class="bill-header">
            <select id="order-type" onchange="setOrderType(this.value)">
                <option value="DINE IN">Dine In</option>
                <option value="TAKE AWAY">Take Away</option>
            </select>
        </div>
        <div class="bill-items" id="bill-items">
            <!-- daftar pesanan -->
        </div>
        <div class="bill-footer">
            <p><span>Subtotal</span><span id="subtotal">Rp 0</span></p>
            <p><b>Total</b><span id="total">Rp 0</span></p>
        </div>
        <div class="clear-wrap">
            <button onclick="clearSale()" class="clear">Clear Sale</button>
        </div>
    </div>

    <div class="btn-row">
        <button onclick="saveBill()">Save Bill</button>
        <button onclick="window.print()">Print Bill</button>
    </div>
    <button onclick="charge()" class="charge">Charge Rp <span id="total-charge">0</span></button>
</div>

<script>
    let pesanan = {};
    let total = 0;
    let orderType = "DINE IN";

    function setOrderType(type) {
        orderType = type;
    }

    function tambahProduk(nama, harga) {
        if (!pesanan[nama]) {
            pesanan[nama] = { qty: 1, harga: harga };
        } else {
            pesanan[nama].qty++;
        }
        renderBill();
    }

    function renderBill() {
        let billItems = document.getElementById("bill-items");
        billItems.innerHTML = "";
        total = 0;
        for (let nama in pesanan) {
            let item = pesanan[nama];
            let sub = item.qty * item.harga;
            total += sub;
            billItems.innerHTML += `<p><span>${nama} x${item.qty}</span><span>Rp ${sub.toLocaleString()}</span></p>`;
        }
        document.getElementById("subtotal").innerText = "Rp " + total.toLocaleString();
        document.getElementById("total").innerText = "Rp " + total.toLocaleString();
        document.getElementById("total-charge").innerText = total.toLocaleString();
    }

    function saveBill() {
        alert("Bill saved! (" + orderType + ")");
    }

    function charge() {
        let bayar = prompt("Masukkan uang pembayaran:");
        if (bayar) {
            let kembali = bayar - total;
            if (kembali >= 0) {
                alert("Order Type: " + orderType +
                      "\nTotal: Rp " + total.toLocaleString() + 
                      "\nBayar: Rp " + parseInt(bayar).toLocaleString() + 
                      "\nKembali: Rp " + kembali.toLocaleString());
            } else {
                alert("Uang kurang!");
            }
        }
    }

    function clearSale() {
        if (confirm("Yakin ingin menghapus invoice?")) {
            pesanan = {};
            total = 0;
            renderBill();
        }
    }
</script>
</body>
</html>
