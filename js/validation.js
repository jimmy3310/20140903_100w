jQuery.validator.addMethod("isMobile", function(value, element) {
  var length = value.length;
  return this.optional(element) || (length == 10 && /^(09)\d{8}$/.test(value));
}, "請正確填寫您的手機號碼");
	

jQuery.validator.addMethod("isHomePhone", function(value, element) {
  var length = value.length;
  return this.optional(element) || (/^0[2-9][0-9]*-[0-9]+$/.test(value));
}, "請正確填寫家裡的電話號碼，格式02-1234567");
	
	
	
jQuery.validator.addMethod("ROC_Citizen_ID_arithmetic",
      function(citizenid, element) {
          var local_table = [10,11,12,13,14,15,16,17,34,18,19,20,21,
                             22,35,23,24,25,26,27,28,29,32,30,31,33];
                         /* A, B, C, D, E, F, G, H, I, J, K, L, M,
                            N, O, P, Q, R, S, T, U, V, W, X, Y, Z */
        var local_digit = local_table[citizenid.charCodeAt(0)-'A'.charCodeAt(0)];
        var checksum = 0;
        checksum += Math.floor(local_digit / 10);
        checksum += (local_digit % 10) * 9;
        /* i: index; p: permission value */
        /* this loop sums from [1] to [8] */
        /* permission value decreases */
        for (var i=1, p=8; i <= 8; i++, p--)
        {
          checksum += parseInt(citizenid.charAt(i)) * p;
        }
        checksum += parseInt(citizenid.charAt(9));    /* add the last number */
        return (
            this.optional(element) || !(checksum % 10)
          );
      }, "身份証ID不正確"
);

  
jQuery.extend(jQuery.validator.messages, {
    required: "必要",
    remote: "Please fix this field.",
    email: "請輸入正確格式的電子郵件",
    url: "請輸入合法的網址",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "請輸入合法的數字",
    digits: "只能輸入整數",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "請再次輸入相同的值",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("請輸入一個長度最多是 {0} 的字符串"),
    minlength: jQuery.validator.format("請輸入一個長度最少是 {0} 的字符串"),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("請輸入一個最大為 {0} 的值"),
    min: jQuery.validator.format("請輸入一個最小為 {0} 的值")
});	