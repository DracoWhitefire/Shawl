"use strict";
function h(b,a,d){var c=String(b.className).split(" ");-1===c.indexOf(a)?"add"===d?(c.push(a),a=!0):a=!1:"del"===d?(c.splice(c.indexOf(a),1),a=!0):a="find"===d?!0:!1;b.className=c.join(" ");return a} function k(b,a){var d=document.createElement("input");d.setAttribute("type","hidden");d.setAttribute("name","y");d.setAttribute("value",a.getFullYear());b.appendChild(d);d=document.createElement("input");d.setAttribute("type","hidden");d.setAttribute("name","m");d.setAttribute("value",a.getMonth()+1);b.appendChild(d);d=document.createElement("input");d.setAttribute("type","hidden");d.setAttribute("name","d");d.setAttribute("value",a.getDate());b.appendChild(d)} function l(){var b=document.getElementById("calCur").textContent.split(" ");return new Date(b[0]+" "+b[1]+" "+b[2])} function m(b){var a,d,c,e,g="January February March April May June July August September October November December".split(" ");for(a=0;a<b.length;a++)b[a].onclick=function(){for(a=0;a<b.length;a++)for(d=this.parentNode.parentNode.parentNode.getElementsByTagName("div"),a=0;a<d.length;a++)h(d[a],"selectedDay","del");h(this.parentNode,"selectedDay","add");c=document.getElementById("calCur");e=l();e.setDate(this.textContent);c.textContent=e.getDate()+" "+g[e.getMonth()]+" "+e.getFullYear()}} function n(b,a,d){var c=document.createElement("input");c.setAttribute("type","submit");c.setAttribute("value",d);c.setAttribute("name",a);b.appendChild(c);return c}function q(b){var a=b.id.replace(/([A-Z])/,"_$1").split("_"),d=l(),c=n(b,a[1],a[1]),e=b.getElementsByTagName("a");"Next"===a[1]?c.onclick=function(){d.setMonth(d.getMonth()+1);k(b,d)}:"Prev"===a[1]&&(c.onclick=function(){d.setMonth(d.getMonth()-1);k(b,d)});for(a=0;a<e.length;a++)b.removeChild(e[a])} function r(b){n(b,"dateSubmit","Choose").onclick=function(){k(b,l())}} window.onload=function(){var b=document.getElementById("daySelect").getElementsByTagName("a"),a=document.getElementById("calPrev"),d=document.getElementById("calNext"),c=document.getElementById("calendar"),e=document.createElement("form"),g=[],f,p;e.setAttribute("id","dateSelector_form");e.setAttribute("method","POST");e.setAttribute("action",document.location.href);for(f=0;f<c.childNodes.length;f++)g.push(c.childNodes[f]);for(f=0;f<g.length;f++)p=c.removeChild(g[f]),e.appendChild(p); c.appendChild(e);if(b instanceof HTMLCollection)for(c=0;c<b.length;c++)b[c].setAttribute("href","#");m(b);for(c=0;c<b.length;c++)if(h(b[c].parentNode,"prevMonth","find")||h(b[c].parentNode,"nextMonth","find"))g=b[c],f=b[c].textContent,f=document.createTextNode(f),g.parentNode.appendChild(f),g.parentNode.removeChild(g),c--;q(a);q(d);r(e)};