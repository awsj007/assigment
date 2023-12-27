/** @format */

jQuery(document).ready(function ($) {
    $("#submitForm").on("click", function (e) {
        e.preventDefault();

        var formData = $("#myForm").serialize();

        $.ajax({
            type: "POST",
            url: ajax_object.ajax_url,
            data: {
                action: "handle_form_submission",
                firstname: $('#myForm input[name="firstname"]').val(),
                lastname: $('#myForm input[name="lastname"]').val(),
                phone: $('#myForm input[name="phone"]').val(),
                email: $('#myForm input[name="email"]').val(),
                // Add any additional data here if needed
            },
            success: function (response) {
                $("#result").html(response);
            },
        });
    });
});
/** @format */

const myTimeout = setTimeout(phonenumbercode, 5000);

function phonenumbercode() {
    // -----Country Code Selection
    var input = document.querySelector("#phone");
    window.intlTelInput(input, {
        separateDialCode: true,
    });
}

let start = 5;
const limit = 5;

function loadData() {
    $.ajax({
        url: `https://jsonplaceholder.typicode.com/posts?_start=${start}&_limit=${limit}`,
        type: "GET",
        success: function (response) {
            if (response.length > 0) {
                response.forEach((item) => {
                    $("#contentother")
                        .append(`<div class="container mx-auto shadow-lg m-4 p-4">
                    <div class="flex gap-2 justify-start align-middle p-2">
                        <span
                            class="text-sm font-bold text-white bg-blue-300 rounded w-10 h-10 flex justify-center align-middle p-2">${item.id}</span>
                        <h1 class="text-lg font-bold">${item.title}</h1>
                    </div>
                
                    <p class="text-sm my-3">${item.body}</p>
                
                    <button class="bg-blue-500 px-5 py-2 text-sm text-center shadow-md rounded-sm text-white font-bold transition"
                        onclick="openModal('modelConfirm')">
                        Subscribe Now
                    </button>
                </div>`);
                });
                start += limit; // Increment the start position for the next load
            } else {
                $("#loadMore").hide(); // Hide the button if no more data is available
            }
        },
    });
}

$(document).ready(function () {
    loadData();

    $("#loadMore").on("click", function () {
        loadData();
    });
});

//popup

window.openModal = function (modalId) {
    document.getElementById(modalId).style.display = "block";
    document.getElementsByTagName("body")[0].classList.add("overflow-y-hidden");
};

window.closeModal = function (modalId) {
    document.getElementById(modalId).style.display = "none";
    document
        .getElementsByTagName("body")[0]
        .classList.remove("overflow-y-hidden");
};

// Close all modals when press ESC
document.onkeydown = function (event) {
    event = event || window.event;
    if (event.keyCode === 27) {
        document
            .getElementsByTagName("body")[0]
            .classList.remove("overflow-y-hidden");
        let modals = document.getElementsByClassName("modal");
        Array.prototype.slice.call(modals).forEach((i) => {
            i.style.display = "none";
        });
    }
};
