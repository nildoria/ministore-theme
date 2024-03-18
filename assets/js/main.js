(function ($) {
  "use strict";

  if(navigator.userAgent.indexOf('iPhone') > -1 )
  {
      document
        .querySelector("[name=viewport]")
        .setAttribute("content","width=device-width, initial-scale=1, maximum-scale=1");
  }
  
  var target_date = new Date().getTime() + 850 * 2980 * 1; // set the countdown date
  var hours, minutes, seconds; // variables for time units

  var countdown = document.getElementById("tiles"); // get tag element
  if (countdown != null) {
    getCountdown();

    setInterval(function () {
      getCountdown();
    }, 1000);

    function getCountdown() {
      // find the amount of "seconds" between now and target
      var current_date = new Date().getTime();
      var seconds_left = (target_date - current_date) / 1000;

      hours = pad(parseInt(seconds_left / 3600));
      seconds_left = seconds_left % 3600;

      minutes = pad(parseInt(seconds_left / 60));
      seconds = pad(parseInt(seconds_left % 60));

      // format countdown string + set tag value
      countdown.innerHTML =
        "</span><span>" +
        hours +
        "</span><b>:</b><span>" +
        minutes +
        "</span><b>:</b><span>" +
        seconds +
        "</span>";
    }
  }
  function pad(n) {
    return not_zero((n < 10 ? "0" : "") + n);
  }

  function not_zero(n) {
    return n.indexOf("-") > -1 ? "00" : n;
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  $.fn.inputFilter = function (inputFilter) {
    return this.on(
      "input keydown keyup mousedown mouseup select contextmenu drop",
      function () {
        if (inputFilter(this.value)) {
          this.oldValue = this.value;
          this.oldSelectionStart = this.selectionStart;
          this.oldSelectionEnd = this.selectionEnd;
        } else if (this.hasOwnProperty("oldValue")) {
          this.value = this.oldValue;
          this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
        } else {
          this.value = "";
        }
      }
    );
  };

  $(window).on("load", function () {
    $("body").addClass("alarnd__loaded");
    if ($(".alarnd--overlay").length !== 0) {
      $("button.single_add_to_cart_button.button")
        .not(".ml_group_enabled_btn")
        .attr("disabled", "disabled");
    }

    setTimeout(function () {
      $(".alarnd--overlay").removeClass("loading");
    }, 1000);
  });

  // $(document).on("click", ".reset_use_search", function (e) {
  //   e.preventDefault();

  //   $("input#alarnd_use_search").val("").trigger("change");
  // });

  $(".alarnd--footer-form input, .alarnd--footer-form textarea").focus(
    function () {
      $(this).closest(".alarnd--single-form-item").addClass("focussed");
    }
  );

  $(".alarnd--footer-form input, .alarnd--footer-form textarea").blur(
    function () {
      if ($(this).val() == "") {
        $(this).closest(".alarnd--single-form-item").removeClass("focussed");
      }
    }
  );

  //Use this inside your document ready jQuery
  $(window).bind("pageshow", function (event) {
    if (event.originalEvent.persisted) {
      window.location.reload();
    }
  });

  // $("#attribute_quanity_custom_val").inputFilter(function(value) {
  //     return /^\d*$/.test(value);    // Allow digits only, using a RegExp
  // });


  $(document).on(
    "click",
    ".alarnd--single-custom-qty .alarnd--single-var-info",
    function () {
      var min = $(this).closest(".alarnd--single-variable").data("min");

      $(this)
        .closest(".alarnd--single-variable")
        .find('input[type="radio"]')
        .prop("checked", true);
      $(this)
        .closest(".alarnd--single-variable")
        .removeClass("alarnd--single-var-labelonly");

      if ($("input#attribute_quanity_custom_val").length === 0) {
        // $(this).find('label').replaceWith('<input type="text" name="attribute_quantity" autocomplete="off" pattern="[0-9]*" class="alarnd_custom_input" inputmode="numeric" id="attribute_quanity_custom_val">');
        $(this)
          .closest("form")
          .find("button.single_add_to_cart_button")
          .attr("disabled", "disabled");
      }
      if (
        $("input#attribute_quanity_custom_val").length !== 0 &&
        $("input#attribute_quanity_custom_val").val() < min
      ) {
        $(this)
          .closest("form")
          .find("button.single_add_to_cart_button")
          .attr("disabled", "disabled");
      }
    }
  );

  $(document).on("click", ".alarnd--custom-qtys-wrap", function () {
    if (!$(this).hasClass("alarnd--single-custom-qty")) {
      $(this)
        .closest("form")
        .find("button.single_add_to_cart_button")
        .removeAttr("disabled");

      if ($(".alarnd--single-custom-qty").length !== 0) {
        $(".tooltip_error").remove();
        $(".alarnd--single-custom-qty")
          .find(".alarnd--single-variable")
          .addClass("alarnd--hide-price");
        // if( $('#attribute_quanity_custom_val').length !== 0 ) {
        //     $('#attribute_quanity_custom_val').replaceWith(allaround_vars.sp_custom_label);
        // }
      }
    }
  });

  $(document).on(
    "change",
    '.alarnd--custom-qtys-wrap input[name="cutom_quantity"]',
    function () {
      var val = $(this).closest(".alarnd--custom-qtys-wrap").data("qty"),
        qtyinput = $(this).closest("form").find('input[name="quantity"]');

      qtyinput.val(val).change();
    }
  );

  $(document).bind("keypress", "#attribute_quanity_custom_val", function (e) {
    if ("attribute_quanity_custom_val" == e.target.id) {
      if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
      }
    }
  });

  $(document).on(
    "change paste keyup",
    "#attribute_quanity_custom_val",
    function (e) {
      var val = e.target.value;

      var val = parseInt(val),
        group = $(e.target).closest(".alarnd--single-variable"),
        min = group.data("min"),
        price = group.data("price");

      var min_msg = allaround_vars.min_msg + " " + min;

      if (e.target.value.length === 0) {
        min_msg = allaround_vars.required_msg;
      }

      var max = 50000;

      if (val == "0") {
        e.target.value = 1;
        val = 1;
      } else if (val > max) {
        // e.target.value = 2000000;
        // val = 2000000;
      }

      var arrNumber = new Array();
      $(".alarnd--custom-qtys-wrap[data-qty]").each(function () {
        if ($(this).attr("data-qty") <= val) {
          arrNumber.push($(this).attr("data-price"));
        }
      });
      var item_price = arrNumber.pop();
      if (typeof item_price === "undefined") {
        item_price = $(".alarnd--single-cart-row").data("reqular-price");
      }

      var intotal = val * item_price;
      //intotal = Math.round(intotal);

      if (intotal % 1 != 0) {
        intotal = intotal.toFixed(1);
      }

      intotal = numberWithCommas(intotal);

      if (min <= val && val <= max) {
        group.removeClass("alarnd--hide-price");
        group
          .find("span.amount")
          .find("bdi")
          .find(".alarnd__wc-price")
          .text(intotal);
        //group.find('span.amount').find('bdi').html( function(i,txt) {return txt.replace(/\d+/, intotal); });
        group
          .find("span.alarnd--single-saving")
          .find(".alarnd__cqty_amount")
          .text(item_price);
        $(e.target)
          .closest("form")
          .find("button.single_add_to_cart_button")
          .removeAttr("disabled");
        $(e.target)
          .closest("form")
          .find('input[name="quantity"]')
          .val(val)
          .change();
        $(e.target)
          .closest(".alarnd--single-var-info")
          .find(".tooltip_error")
          .remove();
        $(e.target).removeClass("error_field");
      } else if (val > max) {
        group.addClass("alarnd--hide-price");
        $(e.target)
          .closest("form")
          .find("button.single_add_to_cart_button")
          .attr("disabled", "disabled");

        $(e.target)
          .closest(".alarnd--single-var-info")
          .find(".tooltip_error")
          .remove();
        $(e.target).after(
          '<div class="tooltip_error"><span class="arrow"></span><span class="text">' +
            allaround_vars.max_msg +
            " " +
            max +
            "</span></div>"
        );
        if (!$(e.target).hasClass("error_field")) {
          $(e.target).addClass("error_field");
        }
      } else {
        group.addClass("alarnd--hide-price");
        $(e.target)
          .closest("form")
          .find("button.single_add_to_cart_button")
          .attr("disabled", "disabled");

        $(e.target)
          .closest(".alarnd--single-var-info")
          .find(".tooltip_error")
          .remove();
        $(e.target).after(
          '<div class="tooltip_error"><span class="arrow"></span><span class="text">' +
            min_msg +
            "</span></div>"
        );
        if (!$(e.target).hasClass("error_field")) {
          $(e.target).addClass("error_field");
        }
      }
    }
  );

  // $("input#alarnd_use_search").bind("change paste keyup", function () {
  //   var $self = $(this),
  //     val = $self.val(),
  //     itemwrap = $(".allaround--service-wraper"),
  //     btn = $(".alarnd_uses_load");

  //   if (val.length === 2 || val.length === 0) {
  //     $.ajax({
  //       type: "POST",
  //       dataType: "html",
  //       url: allaround_vars.ajax_url,
  //       data: {
  //         search: val,
  //         nonce: allaround_vars.nonce,
  //         action: "alarnd_use_search",
  //       },
  //       beforeSend: function () {
  //         btn.addClass("loading");
  //       },
  //       success: function (response) {
  //         btn.removeClass("loading");
  //         if (
  //           response !== 0 &&
  //           $(response).closest(".allaround--uses-not-found").length !== 0
  //         ) {
  //           btn.closest(".allaround--uses-loadmore").slideUp();
  //         } else {
  //           if (
  //             btn.closest(".allaround--uses-loadmore").css("display") == "none"
  //           ) {
  //             btn.closest(".allaround--uses-loadmore").slideDown();
  //           }
  //         }
  //         itemwrap.html(response);
  //       },
  //     });
  //   }

  //   return false;
  // });

  // $(document).on("click", ".alarnd_uses_load", function (e) {
  //   e.preventDefault();

  //   var $self = $(this),
  //     wrap = $self.closest(".alarnd--uses-wrapper"),
  //     itemwrap = wrap.find(".allaround--service-wraper"),
  //     itemcount = itemwrap.find(".allaround--service-single-item").length;

  //   var usesearchbar = $("#alarnd_use_search"),
  //     usesearchval = "";
  //   if (usesearchbar.length !== 0) {
  //     usesearchval = usesearchbar.val();
  //   }

  //   $.ajax({
  //     type: "POST",
  //     dataType: "html",
  //     url: allaround_vars.ajax_url,
  //     data: {
  //       search: usesearchval,
  //       itemcount: itemcount,
  //       nonce: allaround_vars.nonce,
  //       action: "alarnd_use_loadmore",
  //     },
  //     beforeSend: function () {
  //       $self.addClass("loading");
  //     },
  //     success: function (response) {
  //       $self.removeClass("loading");
  //       if (response.length === 0) {
  //         $self.attr("disabled", "dissabled");
  //       }
  //       itemwrap.append(response);
  //     },
  //   });

  //   return false;
  // });

  // $(document).on("click", ".alarn--load-review", function (e) {
  //   e.preventDefault();

  //   var $self = $(this),
  //     wrap = $self.closest(".alarnd--review-wrapper"),
  //     itemwrap = wrap.find(".alarnd--review-groups"),
  //     posts_per_page = itemwrap.data("ppp"),
  //     itemcount = itemwrap.find(".alarnd--single-review").length;

  //   $.ajax({
  //     type: "POST",
  //     dataType: "html",
  //     url: allaround_vars.ajax_url,
  //     data: {
  //       ppp: posts_per_page,
  //       itemcount: itemcount,
  //       nonce: allaround_vars.nonce,
  //       action: "alarnd_review_loadmore",
  //     },
  //     beforeSend: function () {
  //       $self.addClass("loading");
  //     },
  //     success: function (response) {
  //       $self.removeClass("loading");
  //       if (response.length === 0) {
  //         $self.slideUp();
  //       }
  //       itemwrap.append(response);
  //     },
  //   });

  //   return false;
  // });

  $(function () {
    $(document).on("click", ".artwork-input-first", function () {
      $("#alarnd_artwork_file").trigger("click");
    });
    $(document).on("click", ".artwork-input-second", function () {
      $("#alarnd_artwork_file_second").trigger("click");
    });

    $(document).on(
      "click",
      ".alarnd_blog_cat, .allaround--ajax-pagination-wrap a.page-numbers",
      function (e) {
        e.preventDefault();
        var $self = $(this),
          term_id = "",
          base_url = "",
          paged_url = "";

        if ($self.hasClass("alarnd_blog_cat")) {
          if ($self.hasClass("active")) {
            return false;
          }
          $self.addClass("active").siblings().removeClass("active");
          term_id = $self.data("term_id");
        }
        if ($self.hasClass("page-numbers")) {
          if ($(".alarnd_blog_cat.active").length !== 0) {
            term_id = $(".alarnd_blog_cat.active").data("term_id");
          }
          paged_url = $self.attr("href");
        }
        base_url = $(".allaround--pagination-wrap").data("base-url");

        $.ajax({
          type: "POST",
          dataType: "html",
          url: allaround_vars.ajax_url,
          data: {
            term_id: term_id,
            base_url: base_url,
            paged_url: paged_url,
            nonce: allaround_vars.nonce,
            action: "alarnd_blog_fetch",
          },
          beforeSend: function () {
            $(".alarnd--overlay").addClass("loading");
          },
          success: function (response) {
            $(".alarnd--overlay").removeClass("loading");
            $(".allaround--blog-wraper").html(response);
          },
        });

        return false;
      }
    );

    $(document).on("click", ".alarn--single-artwork-pos", function () {
      var $self = $(this),
        key = $self.data("key");

      $self.addClass("selected").siblings().removeClass("selected");
      $self.closest("form").find('input[name="art_position"]').val(key);
      $self
        .closest("form")
        .find("button.alarnd--configure-submit")
        .prop("disabled", false);

      return false;
    });

    // $(".alarnd--sizes-wrap input").inputFilter(function(value) {
    //     return /^-?\d*$/.test(value); });

    $(".alarnd--sizes-wrap input, .alarnd--select-opt-wrapper input").keypress(
      function (e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          //display error message
          // $("#errmsg").html("Digits Only").show().fadeOut("slow");
          return false;
        }
      }
    );

    $(document).on("change", ".alarnd--colors-wrap input", function () {
      var $self = $(this),
        value = $self.val();

      $(".alarnd--color-name").text(value);
    });
    $(".alarnd--colors-wrap label").mouseover(function () {
      var $self = $(this),
        value = $self.data("name");

      $(".alarnd--color-name").text(value);
    });

    $(".upload_another_one").on("change", function (e) {
      if ($("input.upload_another_one").prop("checked")) {
        $(".alarnd-second-upload-wrap").slideDown();
      } else {
        $(".alarnd-second-upload-wrap").slideUp();
      }
    });

    $(".alarnd_artwork_file").on("change", function (e) {
      var file = e.target.files;

      // if file not selected any then fallback immediately
      if (file.length === 0) {
        return false;
      }
      var $self = $(this),
        data = new FormData();

      data.append("alarnd_artwork_file", file[0]);
      data.append("nonce", allaround_vars.nonce);
      data.append("action", "alarnd_artwork_upload");

      $.ajax({
        url: allaround_vars.ajax_url,
        type: "POST",
        data: data,
        cache: false,
        dataType: "json",
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        xhr: function () {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener(
            "progress",
            function (evt) {
              if (evt.lengthComputable) {
                var percentComplete = evt.loaded / evt.total;
                $(".progress-bar").html(
                  Math.round(percentComplete * 100) + "%"
                );
              }
            },
            false
          );
          return xhr;
        },
        beforeSend: function () {
          $self.closest("form").find(".alarnd--progress-bar").slideDown();
          if (
            $self
              .closest("form")
              .prev(".alarnd--configure-header")
              .find("p.alarnd_res_error") !== 0
          ) {
            $self
              .closest("form")
              .prev(".alarnd--configure-header")
              .find("p.alarnd_res_error")
              .remove();
          }
        },
        success: function (response, textStatus, jqXHR) {
          // console.log( response );
          $self.closest("form").find(".alarnd--progress-bar").slideUp();
          if (response.success) {
            $self.closest("form").find(".alarnd--configue-bottom").slideDown();
            $self.closest("form").find(".alarnd--configure-skip").slideUp();
            if (
              $self.closest("form").find('input[name="art_position"]')
                .length !== 0
            ) {
              //$self.closest('form').find('.alarnd--artwork-position-wrap').slideDown();
              //$self.closest('form').find('button.alarnd--configure-submit').prop('disabled', true);
            }

            $self
              .closest(".alarnd--upload-wrap")
              .find("input.alarnd_artwork_id")
              .val(response.data.attachment_id);
            $self
              .closest(".alarnd--upload-wrap")
              .find(".alarnd--artwork-icon")
              .val(response.data.artwork_name);
            $self
              .closest(".alarnd--upload-wrap")
              .find(".alarnd--artwork-icon")
              .attr("data-id", response.data.attachment_id);
          }
          if (response.error) {
            $self
              .closest("form")
              .prev(".alarnd--configure-header")
              .append('<p class="alarnd_res_error">' + response.error + "</p>");
          }
        },
      });

      return false;
    });

    $(document).on("submit", "form.alarnd--configure-cart", function (e) {
      e.preventDefault();

      var $self = $(this),
        getData = $self.serializeArray();

      getData.push({
        name: "action",
        value: "alarnd_cart_configure",
      });
      getData.push({
        name: "nonce",
        value: allaround_vars.nonce,
      });

      $.ajax({
        type: "POST",
        dataType: "json",
        url: allaround_vars.ajax_url,
        data: getData,
        beforeSend: function () {
          $self.find(".alarnd--submit-btn").addClass("loading");
        },
        success: function (response) {
          $self.find(".alarnd--submit-btn").removeClass("loading");
          if (response.success) {
            window.location.replace(allaround_vars.get_cart_url);
          }
        },
      });

      return false;
    });

    $(document).on(
      "click",
      ".ml_add_to_cart_trigger.ml_add_loading",
      function (e) {
        $(this).addClass("ml_loading");
        $("form.cart")
          .find(".single_add_to_cart_button.ml_add_loading")
          .addClass("ml_loading");
      }
    );

    $(document).on(
      "paste keyup keypress",
      ".alarnd--select-qty-body input",
      function (e) {
        var $self = $(this),
          val = $self.val(),
          group = $self.closest(".alarnd--select-qty-body"),
          form = $self.closest("form"),
          modal = $self.closest(".alarnd--info-modal"),
          product_id = modal.data("product_id"),
          inner = $(".alarnd--cart-inner"),
          settings = form.data("settings");

        if (parseInt(val) < 1) {
          $(this).val("");
        }

        var isValid = false;
        var totalQty = 0;
        group.find("input").each(function () {
          if ($(this).val().length !== 0) {
            isValid = true;
            totalQty = totalQty + parseInt($(this).val());
          }
        });

        if (false === isValid) {
          form.find(".single_add_to_cart_button").prop("disabled", true);
          $(".ml_group_enabled_btn").removeClass("addtocart_ready");

          form.find(".alarnd--total-price").find(".alarnd__wc-price").text(0);
          form.find(".alarnd--group-price").find(".alarnd__wc-price").text(0);
          form
            .find(".alarnd--price-by-shirt")
            .find(".alarnd__total_qty")
            .text(0);

          inner.find(".alarnd--total-price").find(".alarnd__wc-price").text(0);
          inner.find(".alarnd--group-price").find(".alarnd__wc-price").text(0);
          inner
            .find(".alarnd--price-by-shirt")
            .find(".alarnd__total_qty")
            .text(0);

          // if (!modal.hasClass("is_already_in_cart")) {
          //   $(".alarnd--next-target-message").slideUp();
          //   $(".alarnd--limit-message").slideUp();
          // }

          if (
            $(document).find(
              '.remove_from_cart_button[data-product_id="' + product_id + '"]'
            ).length === 0
          ) {
            $(".alarnd--next-target-message").slideUp();
            $(".alarnd--limit-message").slideUp();
          }
        } else {
          if (form.find(".alanrd--product-added-message").length !== 0) {
            form.find(".alanrd--product-added-message").slideUp();
          }

          form.find(".single_add_to_cart_button").prop("disabled", false);
          $(".ml_group_enabled_btn").addClass("addtocart_ready");

          var final_price = settings.regular_price;
          var nextTarget, nextPrice, nextItem;
          $.each(settings.data, function (index, value) {
            if (
              0 < totalQty &&
              parseInt(settings.data[0].quantity) >= totalQty
            ) {
              nextTarget =
                settings.data[0] != undefined
                  ? parseInt(settings.data[0].quantity) + 1 - totalQty
                  : null;
              nextPrice =
                settings.data[1] != undefined ? settings.data[1].amount : null;
            } else if (parseInt(value.quantity) < totalQty) {
              nextPrice =
                index === settings.data.length - 1
                  ? null
                  : settings.data[index + 2] != undefined
                  ? settings.data[index + 2].amount
                  : null;
              nextTarget =
                index === settings.data.length - 1
                  ? null
                  : settings.data[index + 1] != undefined
                  ? parseInt(settings.data[index + 1].quantity) + 1 - totalQty
                  : null;
            }

            if (parseInt(value.quantity) < totalQty) {
              final_price =
                index === settings.data.length - 1
                  ? value.amount
                  : settings.data[index + 1].amount;
            }
          });

          if (
            $(document).find(
              '.remove_from_cart_button[data-product_id="' + product_id + '"]'
            ).length === 0
          ) {
            if (nextTarget != undefined && nextPrice != undefined) {
              $(".alarnd--next-target-message").slideDown(function () {
                $(this).find(".ml_next_target").text(nextTarget);
                $(this).find(".alarnd__wc-price").text(nextPrice);
              });
            } else {
              $(".alarnd--next-target-message").slideUp();
            }
          }

          // Restrict each cell from entering extra
          $(".alarn--opt-single-row .three-digit-input").on(
            "input",
            function () {
              // Allow digits only
              this.value = this.value.replace(/\D/g, "");

              // Restrict input to 3 characters
              if (this.value.length > 3) {
                this.value = this.value.slice(0, 3);

                // Add the class to show the tooltip
                $(this).addClass("show-tooltip");

                // Show the tooltip for 2 seconds
                const inputField = $(this);
                const tooltipMessage = inputField.siblings(
                  ".alarnd--limit-tooltip"
                );

                setTimeout(function () {
                  // Remove the class to hide the tooltip
                  inputField.removeClass("show-tooltip");
                }, 2500);
              }
            }
          );

          if (totalQty >= 999) {
            $(".alarnd--limit-message").slideDown();
            form.find(".single_add_to_cart_button").prop("disabled", true);
            return false;
          } else {
            $(".alarnd--limit-message").slideUp();
          }

          var total_price = totalQty * final_price;
          form
            .find(".alarnd--total-price")
            .find(".alarnd__wc-price")
            .text(numberWithCommas(total_price));
          form
            .find(".alarnd--group-price")
            .find(".alarnd__wc-price")
            .text(final_price);
          form
            .find(".alarnd--price-by-shirt")
            .find(".alarnd__total_qty")
            .text(totalQty);

          inner
            .find(".alarnd--total-price")
            .find(".alarnd__wc-price")
            .text(numberWithCommas(total_price));
          inner
            .find(".alarnd--group-price")
            .find(".alarnd__wc-price")
            .text(final_price);
          inner
            .find(".alarnd--price-by-shirt")
            .find(".alarnd__total_qty")
            .text(totalQty);
        }

        // console.log( val );
      }
    );

    // Add Commas to Numbers
    function numberWithCommas(number) {
      var parts = number.toString().split(".");
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      return parts.join(".");
    }

    // Size Chart Popup Trigger
    $(".alarnd__info_trigger").magnificPopup({
      type: "inline",
    });

    $(document).on(
      "paste keyup keypress",
      ".alarnd--sizes-wrap input",
      function (e) {
        var $self = $(this),
          val = $self.val(),
          parent = $self.closest(".alarnd--single-size"),
          form = $self.closest("form"),
          wrap = form.find(".alarnd--single-cart-price"),
          product_id = form.find('button[name="add-to-cart"]').val(),
          overlay = form.find(".alarnd--overlay");
        var max = 50000;

        if (
          $(e.target)
            .closest("form")
            .find(".alarnd--overlay")
            .hasClass("loading")
        ) {
          e.preventDefault();
          return false;
        }

        $(".tooltip_error").remove();

        if (val > max) {
          parent.find(".tooltip_error").remove();
          parent.append(
            '<div class="tooltip_error"><span class="arrow"></span><span class="text">' +
              allaround_vars.max_msg +
              " " +
              max +
              "</span></div>"
          );
          form
            .find("button.single_add_to_cart_button.button.alt")
            .attr("disabled", "disabled");
          e.preventDefault();
          return false;
        } else {
          parent.find(".tooltip_error").remove();
          form
            .find("button.single_add_to_cart_button.button.alt")
            .removeAttr("disabled");
        }

        var sum = 0;
        $(".alarnd--sizes-wrap input").each(function () {
          if ($(this).val() > max) {
            form
              .find("button.single_add_to_cart_button.button.alt")
              .attr("disabled", "disabled");
            e.preventDefault();
            return false;
          }
          sum += Number($(this).val());
        });

        if (!product_id) {
          return false;
        }

        if (sum === 0) {
          form
            .find("button.single_add_to_cart_button.button.alt")
            .attr("disabled", "disabled");
        } else {
          form
            .find("button.single_add_to_cart_button.button.alt")
            .removeAttr("disabled");
        }

        if (e.type != "keypress") {
          overlay.addClass("loading");
          $.ajax({
            type: "POST",
            dataType: "html",
            url: allaround_vars.ajax_url,
            data: {
              total_qty: sum,
              product_id: product_id,
              nonce: allaround_vars.nonce,
              action: "alarnd_get_product_val",
            },
            beforeSend: function () {},
            success: function (response) {
              overlay.removeClass("loading");
              wrap.html(response);
            },
          });
        }
      }
    );

    $(".alarnd_view_select").magnificPopup({
      items: {
        src: "#alarnd__select_options_info",
        type: "inline",
      },
      callbacks: {
        open: function () {
          $("body").addClass("mfp-hide-scroll");
        },
        close: function () {
          $("body").removeClass("mfp-hide-scroll");
        },
      },
    });

    $(".alarnd_view_pricing").magnificPopup({
      items: {
        src: "#alarnd__pricing_info",
        type: "inline",
      },
    });

    $(document).on(
      "click",
      ".alarnd--single-size label, .alarnd--colors-wrap label",
      function (e) {
        e.preventDefault();

        // $('.alarnd_view_select').trigger('click');

        if (
          $(this).closest(".product-item").find(".ml_trigger_details")
            .length !== 0
        ) {
          $(this)
            .closest(".product-item")
            .find(".ml_trigger_details")
            .trigger("click");
        }

        return false;
      }
    );

    $(document).on(
      "click",
      ".single_add_to_cart_button.button.ml_group_enabled_btn",
      function (e) {
        e.preventDefault();

        if ($(this).hasClass("addtocart_ready")) {
          console.log(
            $("#alarnd__select_options_form").find(".ml_add_to_cart_trigger")
              .length
          );
          $("#alarnd__select_options_form")
            .find(".ml_add_to_cart_trigger")
            .trigger("click");
          $(this).addClass("ml_loading");
        } else {
          $(".alarnd_view_select").trigger("click");
        }

        return false;
      }
    );

    $(".alarnd--opt-color span").each(function () {
      var rgb = $(this).css("backgroundColor");
      var colors = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);

      var r = colors[1];
      var g = colors[2];
      var b = colors[3];

      var o = Math.round(
        (parseInt(r) * 299 + parseInt(g) * 587 + parseInt(b) * 114) / 1000
      );

      if (o > 125) {
        $(this).css("color", "black");
      } else {
        $(this).css("color", "white");
      }
    });

    $(document).on("change", ".variation-radios input", function () {
      $(".variation-radios input:checked").each(function (index, element) {
        var $el = $(element);
        var thisName = $el.attr("name");
        var thisVal = $el.attr("value");
        $('select[name="' + thisName + '"]')
          .val(thisVal)
          .trigger("change");
      });
    });
    $(document).on("woocommerce_update_variation_values", function () {
      $(".variation-radios input").each(function (index, element) {
        var $el = $(element);
        var thisName = $el.attr("name");
        var thisVal = $el.attr("value");
        $el.removeAttr("disabled");
        if (
          $(
            "select[name='" + thisName + "'] option[value='" + thisVal + "']"
          ).is(":disabled")
        ) {
          $el.prop("disabled", true);
        }
      });
    });

    $(document).on(
      "change",
      '.variation-radios input[type="radio"]',
      function () {
        if ($(this).closest(".alarnd__select-box").length !== 0) {
          if ($(this).is(":checked")) {
            $(this).closest(".alarnd__select-box").addClass("checked__in");
          } else {
            $(this).find(".alarnd__select-box").removeClass("checked__in");
          }
        } else {
          $(this)
            .closest(".variation-radios")
            .find(".alarnd__select-box")
            .removeClass("checked__in");
        }
      }
    );

    // Target the Disabled Quantity Field T-Shirt Product
    const parentDivs = $(".tshirt-qty-input-field");

    parentDivs.each(function () {
      const inputField = $(this).find("input");
      if (inputField.prop("disabled")) {
        $(this).addClass("disabled_field");
      }
    });

    String.prototype.getValueByKey = function (k) {
      var p = new RegExp("\\b" + k + "\\b", "gi");
      return this.search(p) != -1
        ? decodeURIComponent(
            this.substr(this.search(p) + k.length + 1).substr(
              0,
              this.substr(this.search(p) + k.length + 1).search(/(&|;|$)/)
            )
          )
        : "";
    };

    // jQuery(document).ajaxSend(function(evt, request, settings) {
    //     console.log('settings', settings);
    //   if( settings.url != "" ) {
    //       var param = settings.url.getValueByKey("wc-ajax");
    //       console.log('param', param);
    //     //   if( "add_to_cart" == param && $('.cartin-woocart-pro-mini-cart').length !== 0 ) {
    //     //       jQuery('.cartin-woocart-pro-mini-cart').addClass('cartin_woocart_ajax_starting');
    //     //   }
    //   }

    // });

    $(document).ajaxComplete(function (evt, xhr, settings) {
      // console.log( 'ajaxComplete Triggered' );
      if (settings.url != "") {
        var param = settings.url.getValueByKey("wc-ajax");
        if (
          "get_variation" == param &&
          $(".alarnd--single-variable").length !== 0 &&
          xhr.responseJSON
        ) {
          if (
            xhr.responseJSON !== undefined &&
            xhr.responseJSON.alarnd_data !== undefined
          ) {
            //console.log( xhr.responseJSON.alarnd_data );
            var allattrs = xhr.responseJSON.attributes;
            var lastitem =
              Object.keys(allattrs)[Object.keys(allattrs).length - 1];

            if (xhr.responseJSON.attributes[lastitem]) {
              var selectqty = xhr.responseJSON.attributes.attribute_quantity,
                qtyitem = $("#attribute_quantity-" + selectqty),
                parentitem = qtyitem.closest(".alarnd--single-box-info"),
                wrapitem = parentitem.closest(".alarnd--single-box-wrapper"),
                saving_final = xhr.responseJSON.alarnd_save.replace(
                  "&#039",
                  ""
                );

              if (wrapitem.length !== 0) {
                var saving_label =
                  '<span class="alarnd--single-saving"></span>';
                // console.log( 'alarnd_save', xhr.responseJSON.alarnd_save.length );
                if (saving_final !== undefined && saving_final.length != 0) {
                  saving_label =
                    '<span class="alarnd--single-saving">' +
                    saving_final +
                    "</span>";
                }

                var newinfo = xhr.responseJSON.price_html;
                newinfo += saving_label;

                if (wrapitem.find("span.price").length > 0) {
                  // console.log( 'exists' );
                  wrapitem
                    .find("span.price")
                    .replaceWith(xhr.responseJSON.price_html);

                  if (
                    saving_final !== undefined &&
                    saving_final.length !== 0 &&
                    wrapitem.find("span.alarnd--single-saving").length > 0
                  ) {
                    wrapitem
                      .find("span.alarnd--single-saving")
                      .text(saving_final);
                  } else if (
                    (saving_final === undefined || saving_final.length === 0) &&
                    wrapitem.find("span.alarnd--single-saving").length > 0
                  ) {
                    wrapitem.find("span.alarnd--single-saving").text("");
                  } else {
                    wrapitem.find("span.price").after(saving_label);
                  }
                } else {
                  // console.log( 'first time' );
                  parentitem.after(newinfo);
                }
              } else {
                if (
                  qtyitem
                    .closest(".variation-radios")
                    .find(".alarnd--single-box-wrapper").length !== 0
                ) {
                  if (
                    qtyitem
                      .closest(".variation-radios")
                      .find(".alarnd--single-box-wrapper")
                      .find("span.price").length !== 0
                  ) {
                    qtyitem
                      .closest(".variation-radios")
                      .find(".alarnd--single-box-wrapper")
                      .find("span.price")
                      .text("");
                  }
                  if (
                    qtyitem
                      .closest(".variation-radios")
                      .find(".alarnd--single-box-wrapper")
                      .find("span.price").length !== 0
                  ) {
                    qtyitem
                      .closest(".variation-radios")
                      .find(".alarnd--single-box-wrapper")
                      .find(".alarnd--single-saving")
                      .text("");
                  }
                }
              }
            }

            //console.log( xhr.responseJSON.alarnd_data );

            for (var key in xhr.responseJSON.alarnd_data) {
              var obj = xhr.responseJSON.alarnd_data[key];

              var getitem = $("#" + lastitem + "-" + key),
                item = getitem.closest(".alarnd--single-var-info"),
                item_group = item.closest(".alarnd--single-variable"),
                saving_label = '<span class="alarnd--single-saving"></span>',
                saving_final = xhr.responseJSON.alarnd_data[
                  key
                ].alarnd_save.replace("&#039", "");

              if (saving_final) {
                saving_label =
                  '<span class="alarnd--single-saving">' +
                  saving_final +
                  "</span>";
              }

              var newinfo = xhr.responseJSON.alarnd_data[key].display_price;
              newinfo += saving_label;

              if (item_group.find("span.woocommerce-Price-amount").length > 0) {
                //console.log( 'exists' );
                item_group
                  .find("span.woocommerce-Price-amount")
                  .replaceWith(xhr.responseJSON.alarnd_data[key].display_price);

                if (
                  saving_final &&
                  item_group.find("span.alarnd--single-saving").length > 0
                ) {
                  item_group
                    .find("span.alarnd--single-saving")
                    .text(saving_final);
                } else if (
                  !saving_final &&
                  item_group.find("span.alarnd--single-saving").length > 0
                ) {
                  item_group.find("span.alarnd--single-saving").text("");
                } else {
                  // console.log('first one');
                  item_group
                    .find("span.woocommerce-Price-amount")
                    .after(saving_label);
                }
              } else {
                //console.log( item.html() );
                item.after(newinfo);
              }
              // console.log( xhr.responseJSON.alarnd_data[key] );
            }

            // xhr.responseJSON.alarnd_data.forEach(add_item_to_qantity);

            // console.log( typeof xhr.responseJSON.alarnd_data );
          }
        }
      }
    });

    $(".elementskit-menu-hamburger").on("click", function () {
      $(this).toggleClass("open");
    });


    // Read More JS
    class Accordion {
      constructor(el) {
        // Store the <details> element
        this.el = el;
        // Store the <summary> element
        this.summary = el.querySelector("summary");
        // Store the <div class="content"> element
        this.content = el.querySelector(".content");

        // Store the animation object (so we can cancel it if needed)
        this.animation = null;
        // Store if the element is closing
        this.isClosing = false;
        // Store if the element is expanding
        this.isExpanding = false;
        // Detect user clicks on the summary element
        this.summary.addEventListener("click", (e) => this.onClick(e));
      }

      onClick(e) {
        // Stop default behaviour from the browser
        e.preventDefault();
        // Add an overflow on the <details> to avoid content overflowing
        this.el.style.overflow = "hidden";
        // Check if the element is being closed or is already closed
        if (this.isClosing || !this.el.open) {
          this.open();
          // Check if the element is being openned or is already open
        } else if (this.isExpanding || this.el.open) {
          this.shrink();
        }
      }

      shrink() {
        // Set the element as "being closed"
        this.isClosing = true;

        // Store the current height of the element
        const startHeight = `${this.el.offsetHeight}px`;
        // Calculate the height of the summary
        const endHeight = `${this.summary.offsetHeight}px`;

        // If there is already an animation running
        if (this.animation) {
          // Cancel the current animation
          this.animation.cancel();
        }

        // Start a WAAPI animation
        this.animation = this.el.animate(
          {
            // Set the keyframes from the startHeight to endHeight
            height: [startHeight, endHeight],
          },
          {
            duration: 400,
            easing: "ease-out",
          }
        );

        // When the animation is complete, call onAnimationFinish()
        this.animation.onfinish = () => this.onAnimationFinish(false);
        // If the animation is cancelled, isClosing variable is set to false
        this.animation.oncancel = () => (this.isClosing = false);
      }

      open() {
        // Apply a fixed height on the element
        this.el.style.height = `${this.el.offsetHeight}px`;
        // Force the [open] attribute on the details element
        this.el.open = true;
        // Wait for the next frame to call the expand function
        window.requestAnimationFrame(() => this.expand());
      }

      expand() {
        // Set the element as "being expanding"
        this.isExpanding = true;
        // Get the current fixed height of the element
        const startHeight = `${this.el.offsetHeight}px`;
        // Calculate the open height of the element (summary height + content height)
        const endHeight = `${
          this.summary.offsetHeight + this.content.offsetHeight
        }px`;

        // If there is already an animation running
        if (this.animation) {
          // Cancel the current animation
          this.animation.cancel();
        }

        // Start a WAAPI animation
        this.animation = this.el.animate(
          {
            // Set the keyframes from the startHeight to endHeight
            height: [startHeight, endHeight],
          },
          {
            duration: 400,
            easing: "ease-out",
          }
        );
        // When the animation is complete, call onAnimationFinish()
        this.animation.onfinish = () => this.onAnimationFinish(true);
        // If the animation is cancelled, isExpanding variable is set to false
        this.animation.oncancel = () => (this.isExpanding = false);
      }

      onAnimationFinish(open) {
        // Set the open attribute based on the parameter
        this.el.open = open;
        // Clear the stored animation
        this.animation = null;
        // Reset isClosing & isExpanding
        this.isClosing = false;
        this.isExpanding = false;
        // Remove the overflow hidden and the fixed height
        this.el.style.height = this.el.style.overflow = "";
      }
    }

    document.querySelectorAll("details").forEach((el) => {
      new Accordion(el);
    });


    $("body").on("keyup change", "input.alarnd--otp-input", function () {
      //Switch Input
      if (
        $(this).val().length === parseInt($(this).attr("maxlength")) &&
        $(this).next("input.alarnd--otp-input").length !== 0
      ) {
        $(this).next("input.alarnd--otp-input").focus();
      }

      //Backspace is pressed
      if (
        $(this).val().length === 0 &&
        event.keyCode == 8 &&
        $(this).prev("input.alarnd--otp-input").length !== 0
      ) {
        $(this).prev("input.alarnd--otp-input").focus().val("");
      }

      var otp = "";
      $("input.alarnd--otp-input").each(function () {
        otp += $(this).val();
      });

      $("input.xoo-ml-phone-input").val(otp).change();
    });
	  

  }); /*End document ready*/


  /*Brought click function of fileupload button when text field is clicked*/
  $("#uploadtextfield").click(function () {
    $("#fileuploadfield").click();
  });
  /*Brought click function of fileupload button when browse button is clicked*/
  $("#uploadbrowsebutton").click(function () {
    $("#fileuploadfield").click();
  });
  /*To bring the selected file value in text field*/
  $("#fileuploadfield").change(function () {
    $("#uploadtextfield").val($(this).val());
  });


  // Get all menu items with class .innerPageRoute
  var menuItems = $('.innerPageRoute');

  // Add click event listener to each menu item
  menuItems.on('click', function (event) {
      // Prevent the default behavior of the link
      event.preventDefault();

      // Remove "open" class from .elementskit-menu-toggler
      $('.elementskit-menu-toggler').removeClass('open');

      // Show the loader
      $('#loader').fadeIn();

      // Remove active class from all menu items
      menuItems.removeClass('active');

      // Add active class to the clicked menu item
      $(this).addClass('active');

      // Get the target section ID from the href attribute of the child <a> element
      var targetSectionId = $(this).find('a').attr('href').substring(1);

      setTimeout(function () {
          // Hide #primary and all sections
          $('#primary').css('display', 'none');
          $('.miniPageSection').css('display', 'none');

          // Show the targeted section
          $('#' + targetSectionId).css('display', 'block');

          // Trigger a click event on the first child anchor inside .elementor-gallery__titles-container
          $('.elementor-gallery__titles-container a:first-child').click();
          $('.product-filter button.filter-button:first-child').click();

          // Scroll to #miniSiteHeader
          //$("html, body").animate({
          //    scrollTop: $('#miniSiteHeader').offset().top
          //}, 0); // Instantly scroll, adjust as needed

      }, 400);

      setTimeout(function () {
          $('#loader').fadeOut();
      }, 800);
  });

  $('.innerPageRoute.home-page').addClass('active');

  $(window).on('load', function () {
      $('.product-filter button.filter-button:first-child').click();
  });


})(jQuery);
