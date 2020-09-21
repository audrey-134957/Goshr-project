var categoryForm = {

    toggle: function () {
        var editBtn = $('.btn-edit-cat');

        var edit = $('.form--edit').closest(editBtn);

        editBtn.on('click', function () {
            // alert('ok');
            $(this).parent()
                .parent()
                .next(edit)
                .toggleClass('is-hidden');
        });
    }
};


categoryForm.toggle();