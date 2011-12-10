//Quick and dirty query Getter object.
function urlQueryGetter(url){
    //array to store params
    var qParam = new Array();
    //function to get param
    this.getParam = function(x){
    return qParam[x];
    }

    //parse url 
    query = url.substring(url.indexOf('?')+1);
    query_items = query.split('&');
    for(i=0; i<query_items.length;i++){
        s = query_items[i].split('=');
        qParam[s[0]] = s[1];
    }

} 
          function createTab(){
              var modid =location.search;
              var fullme = new urlQueryGetter(location.href);
              var updateid = fullme.getParam('update');//get the mod id from modedit.php
              var modid = fullme.getParam('id');
              alert(modid);
              var filename =location.pathname; 

              var mydiv = document.getElementById("region-main");              
              var tabdiv = document.createElement("div");             
              tabdiv.className = "tabtree";
              var ulist = document.createElement("ul"); 
              ulist.className = "tabrow0";
             
              
              for (var i=0; i<3; i++) {
                    var llist = document.createElement('li');
                    var links = document.createElement('a');
                    var spanforname = document.createElement('span');
                    
                    if (i===0) {
                        links.title = "Preview";
                        links.href = "view.php"+modid;                        
                       // spanforname.innerHTML = "Preview";
                        spanforname.appendChild(document.createTextNode("Preview")); 
                        links.appendChild(spanforname);
                        llist.appendChild(links);
                    } else if (i===1) {
                        links.title = "Edit Content";
                        links.href = "google.com";                       
                       // spanforname.innerHTML = "Edit Content";
                        spanforname.appendChild(document.createTextNode("Edit Content"));
                        links.appendChild(spanforname);
                        llist.appendChild(links);
                    } else if (i===2) {
                        links.title = "Setting";
                        links.href = "google.com";                        
                        spanforname.appendChild(document.createTextNode("Settings"));
                        links.appendChild(spanforname);
                        llist.appendChild(links);
                    }               
                    
                    ulist.appendChild(llist);
              }
             
              tabdiv.appendChild(ulist);
              var tabses = mydiv.childNodes[1].insertBefore(tabdiv, mydiv.childNodes[1].childNodes[1]);
              return tabses;//mydiv.appendChild(document.createTextNode("Edit Content"));;
          }
          //document.onload = createTab();
            
            