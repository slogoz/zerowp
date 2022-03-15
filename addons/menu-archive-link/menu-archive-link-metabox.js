'use struct';

jQuery(document).ready(function ($) {
    $('#submit-post-type-archives').click(function (event) {
        event.preventDefault();

        let postTypes = [];
        $('#post-type-archive-checklist li :checked').each(function () {
            postTypes.push($(this).val());
        })

        $.post(ajaxurl, {
                action: 'menu-archive-links',
                posttypearchive_nonce: MenuArchiveLink.nonce,
                post_types: postTypes
            },

            function (response) {
                $('#menu-to-edit').append(response);
            }
        );
    })
});