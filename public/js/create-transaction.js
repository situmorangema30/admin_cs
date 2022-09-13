const selectProduct = document.querySelector("#products_id_select");
const selectVoucher = document.querySelector("#voucher_id");
const tableProductBody = document.querySelector("#table-products-tbody");

let products = [];
let vouchers = [];

const getProducts = async () => {
    const response = await fetch("/api/products/all");
    const data = await response.json();
    products = data;

    updateSelectProduct();
};

const getVouchers = async () => {
    const response = await fetch("/api/vouchers/all");
    const data = await response.json();
    vouchers = data;

    selectVoucher.innerHTML = '<option value="0" selected>Choose One</option>';

    vouchers.map((voucher) => {
        let { id, code, type, disc_value, start_date, end_date, status } =
            voucher;
        start_date = start_date.split("-");
        end_date = end_date.split("-");

        start_date = new Date(
            parseInt(start_date[0]),
            parseInt(start_date[1]),
            parseInt(start_date[2])
        );
        end_date = new Date(
            parseInt(end_date[0]),
            parseInt(end_date[1]),
            parseInt(end_date[2])
        );

        let day = new Date().getDate();
        let month = new Date().getMonth();
        let year = new Date().getFullYear();

        let today = new Date(year, month + 1, day);

        if (today >= start_date && today <= end_date && status == "1") {
            const discPrice = `${parseInt(disc_value)}${type == 2 ? "%" : ""}`;
            selectVoucher.innerHTML += `
                <option value="${id}">${code} | Discount ${discPrice}</option>
            `;
        }
    });
};

getProducts();
getVouchers();

const updateSelectProduct = () => {
    selectProduct.innerHTML = '<option value="" selected>Choose One</option>';

    products.map((product) => {
        const { id, name, status } = product;

        if (status == 1) {
            selectProduct.innerHTML += `
                <option value="${id}">${name}</option>
            `;
        }
    });
};

const addProduct = () => {
    const product = [...selectProduct.selectedOptions][0];
    const qty = parseInt(document.querySelector("#add-product-qty").value);

    if (!qty || product.value === "") {
        return;
    }

    let newProduct = products.find(
        (item) => item.id == parseInt(product.value)
    );

    product.remove();

    products = products.filter((item) => item.id !== newProduct.id);

    if (newProduct) {
        const { id, name, price, purchase_price } = newProduct;
        const tableProductBodyChildren = tableProductBody.childElementCount;

        const list = document.createElement("tr");
        list.innerHTML = `
            <td>
                <input type="hidden" class="form-control" id="product_id" name="product_id[]" value="${id}">
                <input type="text" class="form-control" id="product_name" name="product_name[]" value="${name}" readonly>
            </td>
            <td>
                <input type="number" class="form-control" id="qty" name="qty[]" onchange="updatePrice(this)" onkeydown="updatePrice(this)" value="${qty}">
            </td>
            <td>
                <input type="hidden" class="form-control" id="price_satuan" name="price_satuan[]" value="${price}">
                <input type="hidden" class="form-control" id="price_total" name="price_total[]" value="${
                    price * qty
                }">
                <input type="hidden" class="form-control" id="price_purchase_satuan" name="price_purchase_satuan[]" value="${purchase_price}">
                <input type="text" class="form-control" id="price_purchase_total" name="price_purchase_total[]" value="${
                    purchase_price * qty
                }" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger mb-3" onclick="removeProduct(this)"><i class="fas fa-trash"></i> Remove</button>
            </td>
        `;

        if (
            tableProductBody.children[tableProductBodyChildren - 2] !==
            undefined
        ) {
            tableProductBody.insertBefore(
                list,
                tableProductBody.children[tableProductBodyChildren - 2]
            );
        } else {
            tableProductBody.insertBefore(
                list,
                tableProductBody.lastElementChild
            );
        }

        selectVoucher.disabled = false;
        updateTotalPrice();
    }
};

const updateTotalPrice = () => {
    if (selectVoucher.value != 0) {
        const newVoucher = vouchers.find(
            (voucher) => voucher.id == selectVoucher.value
        );

        const addProductDiscountTotal = document.querySelector(
            "#add-product-discount-total"
        );

        if (addProductDiscountTotal === null) {
            const list = document.createElement("tr");
            list.setAttribute("data-keterangan", "no");
            list.innerHTML = `
                <td class="text-center font-weight-bold" colspan="2">Discounted Value</td>
                <td class="text-center font-weight-bold" id="add-product-discount-total">${parseInt(
                    newVoucher.disc_value
                )}${newVoucher.type == 2 ? "%" : ""}</td>
                <td></td>
            `;

            tableProductBody.insertBefore(
                list,
                tableProductBody.lastElementChild
            );
        } else {
            addProductDiscountTotal.innerText =
                parseInt(newVoucher.disc_value) +
                `${newVoucher.type == 2 ? "%" : ""}`;
        }
    } else {
        const tableProductBodyChildren = tableProductBody.childElementCount;

        if (
            tableProductBody.children[tableProductBodyChildren - 2] !==
                undefined &&
            tableProductBody.children[
                tableProductBodyChildren - 2
            ].hasAttribute("data-keterangan")
        ) {
            tableProductBody.children[tableProductBodyChildren - 2].remove();
        }
    }

    let reducePrice = 100;
    let reduceType = 2;
    let subTotalPrice = 0;
    let totalPrice = 0;
    let totalPurchasePrice = 0;
    let totalBeforeDiscount = 0;

    const tr = [...tableProductBody.children];
    const subTotal = document.querySelector("#sub_total");
    const total = document.querySelector("#total");
    const purchaseTotal = document.querySelector("#total_purchase");
    const purchaseTotalBeforeDiscount = document.querySelector(
        "#total_before_discount"
    );
    const addProductTotal = document.querySelector("#add-product-total");

    tr.forEach((e) => {
        if (!e.hasAttribute("data-keterangan")) {
            const prices = [...e.children[2].children];
            totalPrice += parseInt(prices[1].value);
            totalPurchasePrice += parseInt(prices[3].value);
        }
    });

    const selectedVoucher = parseInt(
        [...selectVoucher.selectedOptions][0].value
    );

    if (selectedVoucher != 0) {
        const { type, disc_value } = vouchers.find(
            (voucher) => voucher.id === selectedVoucher
        );

        const discValue = parseInt(disc_value);
        reducePrice = discValue;
        reduceType = parseInt(type);
    }

    subTotalPrice = totalPrice;
    if (reducePrice !== 100) {
        if (reduceType == 2) {
            totalPrice = totalPrice - totalPrice * (reducePrice / 100);
        } else {
            totalPrice = totalPrice - reducePrice;
        }
    } else {
        totalPrice = totalPrice * (reducePrice / 100);
    }
    totalBeforeDiscount = totalPurchasePrice;
    if (reducePrice !== 100) {
        if (reduceType == 2) {
            totalPurchasePrice =
                totalPurchasePrice - totalPurchasePrice * (reducePrice / 100);
        } else {
            totalPurchasePrice = totalPurchasePrice - reducePrice;
        }
    } else {
        totalPurchasePrice = totalPurchasePrice * (reducePrice / 100);
    }

    subTotal.value = subTotalPrice;
    total.value = totalPrice;
    purchaseTotal.value = totalPurchasePrice;
    purchaseTotalBeforeDiscount.value = totalBeforeDiscount;
    addProductTotal.innerText = totalPurchasePrice;
};

const updatePrice = (e) => {
    const qty = parseInt(e.value);
    const priceSatuan = parseInt(
        e.parentElement.parentElement.children[2].children[0].value
    );
    const priceTotal = e.parentElement.parentElement.children[2].children[1];
    const pricePurchaseSatuan = parseInt(
        e.parentElement.parentElement.children[2].children[2].value
    );
    const pricePurchaseTotal =
        e.parentElement.parentElement.children[2].children[3];

    priceTotal.value = qty * priceSatuan;
    pricePurchaseTotal.value = qty * pricePurchaseSatuan;

    updateTotalPrice();
};

removeProduct = async (e) => {
    e.parentElement.parentElement.remove();

    const id = e.parentElement.parentElement.children[0].children[0].value;

    const response = await fetch(`/api/products/${id}`);
    const data = await response.json();

    products = [...products, data];

    updateSelectProduct();
    updateTotalPrice();
};
