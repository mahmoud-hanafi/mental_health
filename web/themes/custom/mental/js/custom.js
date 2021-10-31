$(".custom-radio").click(function () {
    $(this).siblings().find("label").removeClass("active");
    $(this).find("label").addClass("active");
});