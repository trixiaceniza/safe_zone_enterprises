const c=document.getElementById('bg'),x=c.getContext('2d');
function fit(){c.width=innerWidth;c.height=innerHeight}fit();addEventListener('resize',fit);
const P=Array.from({length:70},()=>({x:Math.random()*c.width,y:Math.random()*c.height,vx:(Math.random()-.5)*.6,vy:(Math.random()-.5)*.6}));
(function d(){x.clearRect(0,0,c.width,c.height);x.fillStyle='#00d4ff';
P.forEach(p=>{p.x=(p.x+p.vx+c.width)%c.width;p.y=(p.y+p.vy+c.height)%c.height;x.fillRect(p.x,p.y,2,2)});requestAnimationFrame(d)})();
