<div id="viewOverlay" class="overlay">
  <div class="modal" id="viewContent"></div>
</div>
<script>
function openView(id){
  fetch('view_note.php?id='+id).then(r=>r.text()).then(html=>{
    document.getElementById('viewContent').innerHTML=html;
    document.getElementById('viewOverlay').style.display='flex';
  });
}
document.addEventListener('DOMContentLoaded',function(){
  document.getElementById('viewOverlay').addEventListener('click',function(e){
    if(e.target.id==='viewOverlay'){e.target.style.display='none';}
  });
});
</script>
