<style>
.overlay{position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);display:none;align-items:center;justify-content:center;}
.overlay .modal{background:#fff;padding:20px;max-width:400px;width:100%;}
</style>
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
