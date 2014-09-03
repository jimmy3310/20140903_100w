
		function checkID( id ) {
		  tab = "ABCDEFGHJKLMNPQRSTUVXYWZIO"                     
		  A1 = new Array (1,1,1,1,1,1,1,1,1,1,2,2,2,2,2,2,2,2,2,2,3,3,3,3,3,3 );
		  A2 = new Array (0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5,6,7,8,9,0,1,2,3,4,5 );
		  Mx = new Array (9,8,7,6,5,4,3,2,1,1);

		  if ( id.length != 10 ) return false;
		  i = tab.indexOf( id.charAt(0) );
		  if ( i == -1 ) return false;
		  sum = A1[i] + A2[i]*9;

		  for ( i=1; i<10; i++ ) {
			v = parseInt( id.charAt(i) );
			if ( isNaN(v) ) return false;
			sum = sum + v * Mx[i];
		  }
		  if ( sum % 10 != 0 ) return false;
		  return true;
		}
		
		
		function CheckMobile(obj) {
			if (obj.search(/^(09)\d{8}$/)!=-1 ) {
					return false;
			} else { 
			return true;
			}
		}
		
		
		
		function logout() {
                    $.ajax({
                        type: "GET",
                        url: 'logout.php',
                        cache: false,
                        data:'',
                        error: function(){
                            alert('Ajax request 發生錯誤');
                        },
                        success: function(data){
							alert('已成功登出');
							window.location.href = "http://www.1card.com.tw/mp/";
                        }
                    });


		}
		

		