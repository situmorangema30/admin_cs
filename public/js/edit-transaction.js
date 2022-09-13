const selectVoucher = document.querySelector("#voucher_id");
const tableProductBody = document.querySelector("#table-products-tbody");
const idTransaction = {
    ...document.querySelector(".edit-transaction").dataset,
}["id"];

let vouchers = [];

const getVouchers = async () => {
    let response = await fetch(`/api/voucher-usages/${idTransaction}`);
    const dataProduct = await response.json();

    response = await fetch("/api/vouchers/all");
    const data = await response.json();
    vouchers = data;

    selectVoucher.innerHTML =
        dataProduct.length > 0
            ? "<option value='0'>Choose One</option>"
            : "<option value='0' selected>Choose One</option>";

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
            selectVoucher.innerHTML +=
                dataProduct.length > 0 && id == dataProduct[0].vouchers_id
                    ? `
                <option value="${id}" selected>${code} | Discount ${discPrice}</option>
            `
                    : `
                <option value="${id}">${code} | Discount ${discPrice}</option>
            `;
        }

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
        }
    });
};

getVouchers();

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

const updateDiscountTotalPrice = async () => {
    const purchaseTotal = document.querySelector("#total_purchase");
    const purchaseTotalBeforeDiscount = document.querySelector(
        "#total_before_discount"
    );

    let response = await fetch("/api/vouchers/all");
    let data = await response.json();
    vouchers = data;

    response = await fetch(`/api/voucher-usages/${idTransaction}`);
    try {
        data = (await response.json())[0]["vouchers_id"];
    } catch (error) {
        data = null;
    }

    if (data !== null) {
        vouchers.map((voucher) => {
            const { id, disc_value } = voucher;

            if (id === data) {
                purchaseTotal.value = parseInt(purchaseTotal.value);
                purchaseTotalBeforeDiscount.value =
                    parseInt(purchaseTotal.value) * (disc_value / 100);
            }
        });
    } else {
        purchaseTotal.value = parseInt(purchaseTotal.value);
        console.log(purchaseTotal);
        purchaseTotalBeforeDiscount.value = parseInt(purchaseTotal.value);
    }
};

updateDiscountTotalPrice();

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
