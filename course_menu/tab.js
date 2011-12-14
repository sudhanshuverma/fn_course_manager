M.block_course_menu = {
    
    createTab: function() {        
        var myDiv = document.getElementById("region-main");       
        var tabDiv = document.createElement("div");
        tabDiv.className = "tabtree";
        var ulist = document.createElement("ul");
        myDiv.insertBefore(tabDiv,myDiv.firstChild);
        tabDiv.appendChild(ulist);
        for(var i=0; i<3; i++) {
            ulist.appendChild(document.createElement('li')) ;         
        }
        
        /* var li_one = document.createElement('li');         
        ulist.className = "tabrow0";
        tabdiv.appendChild(document.createTextNode("Preview"));
        mydiv.childNodes[1].insertBefore(tabdiv, mydiv.childNodes[1].childNodes[1]); */
       // for (var i=0; i<3; i++) {  
            
//            if (i===0) {
//                var li_one = document.createElement('li');
//                var li_one_link = document.createElement('a');
//                var li_one_span = document.createElement('span');
//                li_one_link.title = "Preview";
//                li_one_link.href = "view.php"+modid;
//                //spanforname.innerHTML = "Preview";
//                li_one_span.appendChild(document.createTextNode("Preview"));
//                li_one_link.appendChild(li_one_span);
//                li_one.appendChild(li_one_link);
//                ulist.appendChild(li_one);
//                
//            } else if (i===1) {                
//                var li_two = document.createElement('li');
//                var li_two_link = document.createElement('a');
//                var li_two_span = document.createElement('span');
//                li_two_link.title = "Preview";
//                li_two_link.href = "view.php"+modid;
//                //spanforname.innerHTML = "Preview";
//                li_two_span.appendChild(document.createTextNode("Preview"));
//                li_two_link.appendChild(li_two_span);
//                li_two.appendChild(li_two_link);
//                ulist.appendChild(li_two);
//                
//           } else if (i===2) {
//                var li_three = document.createElement('li');
//                var li_three_link = document.createElement('a');
//                var li_three_span = document.createElement('span');
//                li_three_link.title = "Preview";
//                li_three_link.href = "view.php"+modid;
//                //spanforname.innerHTML = "Preview";
//                li_three_span.appendChild(document.createTextNode("Preview"));
//                li_three_link.appendChild(li_three_span);
//                li_three.appendChild(li_three_link);
//                ulist.appendChild(li_three);
//           } 
//                
//                
//        }
//             
//              tabdiv.appendChild(ulist);
//              mydiv.appendChild(tabdiv)
              //return ;
              //var tabses = mydiv.childNodes[1].insertBefore(tabdiv, mydiv.childNodes[1].childNodes[1]);
//              return tabses;//
             // return mydiv.appendChild(document.createTextNode("Edit Content"));
//alert( ulist.className);
    }

}