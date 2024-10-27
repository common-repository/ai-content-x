/** Core */
var aicxpro = {
  elm: {
    show(elm) {
      if (typeof elm == "string") {
        elm = document.querySelector(elm);
      }
      if (!elm) return;
      elm.style.display = "block";
    },
    hide(elm) {
      if (typeof elm == "string") {
        elm = document.querySelector(elm);
      }
      if (!elm) return;
      elm.style.display = "none";
    },
    toggle(elm) {
      if (typeof elm == "string") {
        elm = document.querySelector(elm);
      }
      if (!elm) return;
      if (elm.style.display == "none") {
        elm.style.display = "block";
      } else {
        elm.style.display = "none";
      }
    },
  },
  toggleCustomizerOptions(elm) {
    var checked = elm.checked;
    var target = document.querySelector(
      "#ai_content_x_prompt_customizer_options"
    );
    if (checked) {
      target.style.display = "block";
    } else {
      target.style.display = "none";
    }
  },
  queue: {
    run(elm, reload) {
      if (!elm.dataset.postId) {
        return;
      }
      if (!confirm("Are you sure you want to run the post queue?")) {
        return false;
      }
      var postID = elm.dataset.postId;
      elm.disabled = true;
      elm.innerHTML = "Please wait...";
      elm.classList.add("ai-content-x-wait");
      jQuery.ajax({
        type: "post",
        url: ajax_var.url,
        data:
          "action=" +
            ajax_var.run +
            "&nonce=" +
            ajax_var.nonce +
            "&post_id=" +
            postID || "",
        success: function (result) {
          if (result == "success") {
            if (reload) {
              setTimeout(function () {
                location.reload();
              }, 2000);
              aicxpro.alert(
                "Cron run successfully, reloading page...",
                "success"
              );
              return;
            }
            aicxpro.alert("Cron run successfully", "success");
          } else {
            aicxpro.alert(result, "error");
            elm.disabled = false;
            elm.innerHTML = elm.dataset.text;
            elm.classList.remove("ai-content-x-wait");
          }
        },
      });
    },
  },
  log: {
    clear(elm) {
      if (!confirm("Are you sure you want to clear log?")) {
        return false;
      }
      elm.disabled = true;
      elm.innerHTML = "Please wait...";
      elm.classList.add("ai-content-x-wait");

      jQuery.ajax({
        type: "post",
        url: ajax_var.url,
        data: "action=" + ajax_var.clear_log + "&nonce=" + ajax_var.nonce,
        success: function (result) {
          if (result == "success") {
            elm.disabled = false;
            elm.innerHTML = elm.dataset.text;
            elm.classList.remove("ai-content-x-wait");
            aicxpro.alert("Log cleared successfully", "success");
            document.querySelector("#ai-content-x-logs-container").innerHTML =
              "";
          } else {
            elm.disabled = false;
            elm.innerHTML = elm.dataset.text;
            elm.removeClass("ai-content-x-wait");
            aicxpro.alert("Something went wrong", "error");
          }
        },
      });
    },
  },
  alert(message, type) {
    var alert = document.createElement("div");
    alert.classList.add("ai-content-alert");
    alert.classList.add(type);
    alert.innerHTML = message;
    alert.onclick = function () {
      alert.classList.remove("show");
      setTimeout(function () {
        alert.remove();
      }, 500);
    };
    document.body.appendChild(alert);
    setTimeout(function () {
      alert.classList.add("show");
    }, 100);
    setTimeout(function () {
      alert.classList.remove("show");
      setTimeout(function () {
        alert.remove();
      }, 500);
    }, 5000);
  },
  options: {
    save(form) {
      var data = jQuery(form).serialize();
      /* Because serialize() ignores unset checkboxes and radio buttons: */
      var unchecked = form.querySelectorAll(
        "input[type=checkbox]:not(:checked)"
      );
      for (var i = 0; i < unchecked.length; i++) {
        data += "&" + unchecked[i].name + "=0";
      }

      data +=
        "&action=" +
        ajax_var.save_options +
        "&nonce=" +
        ajax_var.nonce +
        "&key=" +
        form.dataset.key;
      console.log("form", form);
      form.querySelector("[type=submit]").disabled = true;
      jQuery.ajax({
        type: "post",
        url: ajax_var.url,
        data: data,
        success: function (result) {
          if (result == "success") {
            aicxpro.alert("Settings saved successfully", "success");
          } else {
            aicxpro.alert(result, "error");
          }
          form.querySelector("[type=submit]").disabled = false;
        },
      });
      return false;
    },
  },
};
