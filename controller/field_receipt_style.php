<style>

:root{
    --mainColor: #1a1a1a;
    --textColor: #333;
    --lightText: #555;
    --borderColor: #ddd;
    --tableHeader: #f2f2f2;
    --highlight: #007bff;
}

body{
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 13px;
    color: var(--textColor);
    background: #fff;
    margin: 0;
    padding: 0;
}

.sales_receipt{
    width: 90%;
    max-width: 580px;
    margin: auto;
    padding: 15px;
    border: 1px solid var(--borderColor);
    border-radius: 6px;
    background: #ffffff;
}

.receipt_title h2{
    font-size: 18px;
    text-align: center;
    margin: 8px 0 12px 0;
    text-transform: uppercase;
    color: var(--mainColor);
}

.receipt_section{
    margin-top: 14px;
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 1px solid var(--borderColor);
}

.receipt_section h4{
    margin: 0 0 6px 0;
    font-size: 14px;
    color: var(--mainColor);
    font-weight: bold;
    text-transform: uppercase;
}

.receipt_section p{
    margin: 2px 0;
    font-size: 13px;
    color: var(--lightText);
}

.searchTable{
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.searchTable thead tr{
    background: var(--tableHeader);
}

.searchTable td{
    border: 1px solid var(--borderColor);
    padding: 7px;
    font-size: 13px;
}

.searchTable td:first-child{
    width: 70%;
}

.searchTable td:last-child{
    text-align: right;
    font-weight: bold;
}

.total_section{
    margin-top: 15px;
    padding-top: 10px;
    border-top: 1px solid var(--borderColor);
}

.total_section p{
    font-size: 13px;
    margin: 3px 0;
}

strong{
    color: var(--mainColor);
}

.sold_by{
    margin-top: 20px;
    font-size: 13px;
}
.sales_receipt{
    display:flex;
    justify-content: left;
    gap:.3rem;
    align-items: center;
    margin:0;
    padding:0;
}
.receipt_logo{
    width:80px;
    height:80px;
}
.receipt_logo img{
    width:100%;
    height:100%;
}
.logo_details h2{
    text-transform: uppercase;
    font-size:.9rem;
    margin:0;
    padding:0;
}
.logo_details p{
    margin:0;
    padding:0;
}
@media print {
    body{
        background: #fff;
    }
    .sales_receipt{
        border: none;
        width: 100%;
        padding: 0;
    }
}
</style>