$(function () {
    const $form = $("#registrationForm");

    function setError($field, message) {
        $field.addClass("invalid");
        $field.closest(".field").find(".field-error").text(message);
    }

    function clearError($field) {
        $field.removeClass("invalid");
        $field.closest(".field").find(".field-error").text("");
    }

    $form.find("input, select, textarea").on("input change", function () {
        clearError($(this));
        if (this.id === "declaration") $(".declaration-error").text("");
    });

    $form.on("submit", function (event) {
        let valid = true;
        const name = $("#full_name").val().trim();
        const email = $("#email").val().trim();
        const phone = $("#phone").val().trim();
        const dob = $("#dob").val();
        const address = $("#address").val().trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const phonePattern = /^[0-9+\-\s()]{10,15}$/;

        $form.find(".field-error").text("");
        $form.find(".invalid").removeClass("invalid");

        if (name.length < 2) { setError($("#full_name"), "Enter at least 2 characters."); valid = false; }
        if (!emailPattern.test(email)) { setError($("#email"), "Enter a valid email address."); valid = false; }
        if (!phonePattern.test(phone)) { setError($("#phone"), "Enter a valid phone number."); valid = false; }
        if (!dob || new Date(dob + "T00:00:00") > new Date()) { setError($("#dob"), "Select a valid date of birth."); valid = false; }
        if (!$("#gender").val()) { setError($("#gender"), "Please select an option."); valid = false; }
        if (!$("#course").val()) { setError($("#course"), "Please select a course."); valid = false; }
        if (address.length < 8) { setError($("#address"), "Enter your complete address."); valid = false; }
        if (!$("#declaration").is(":checked")) { $(".declaration-error").text("Please accept the declaration."); valid = false; }

        if (!valid) {
            event.preventDefault();
            const $first = $form.find(".invalid").first();
            if ($first.length) $first.trigger("focus");
            return;
        }

        $("#submitButton").prop("disabled", true).text("Submitting...");
    });
});
