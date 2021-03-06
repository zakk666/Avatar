//  This is a test line.
//	Elements in the scene
var camera, scene, renderer,
geometry, material, mesh;

var projector;

var isRendering = true;
var isRedirected = false;

var mouseX = 0, mouseY = 0,

windowHalfX = window.innerWidth / 2,
windowHalfY = window.innerHeight / 2;

//	The size of the app
var homeWidth = 1400;
var homeHeight = 860;

//	The viarables for perlin noise
var perlin;

var perlin3DIncrement = 0.1;
var zoff = 0;
var zincrement = 0.01;

var perlinNoise = new SimplexNoise();

var perlinStep = 200;
//			var perlinXSeg = homeWidth/perlinStep;
//			var perlinYSeg = homeHeight/perlinStep;
var perlinXSeg = 13;
var perlinYSeg = 13;

var perlinGrid = new Array();
for (var i=0; i<perlinXSeg; i++){
	perlinGrid[i] = new Array();
};

//	The virables for the avatar icons.
var avatarsGeo = new Array();
var avatarsTexture = new Array();
var avatarsMaterial = new Array();
var avatarsMesh = new Array();

var avatarSize = perlinStep;

//	For hover status
var lastHoverAvatarName = "";
var currentHoveAvatarName = "";
var hoverTranslate = new Array();
var hoverTranslateThreshold = 250;
var hoverTranslateSpeed = 20;
var hoverTranslateAccerlertion = 5;

//	For click
var clickedAvatarName = "";

for (var i=0; i<perlinXSeg; i++){
	avatarsGeo[i] = new Array();
	avatarsTexture[i] = new Array();
	avatarsMaterial[i] = new Array();
	avatarsMesh[i] = new Array();
	
	hoverTranslate[i] = new Array();
	for(var j=0; j<perlinYSeg; j++){
		hoverTranslate[i][j] = 0;
	}
};

init();
animate();

function init() {
    
    camera = new THREE.Camera( 30, homeWidth / homeHeight, 1, 10000 );
    camera.position.z = 2500;
	// camera.position.x = 400;
	// camera.position.y = 200;

    scene = new THREE.Scene();
	
	perlin = new SimplexNoise();
	
//				geometry = new THREE.Rectangle(200,200);
	var callbackAvatar = function(x, y){
		avatarsGeo[x][y] = new THREE.Plane( avatarSize, avatarSize, 1, 1 );
		avatarsMaterial[x][y] = new THREE.MeshBasicMaterial( { color:0xffffff, map: texture } );
		avatarsMesh[x][y] = new THREE.Mesh( avatarsGeo[x][y], avatarsMaterial[x][y] );
		avatarsMesh[x][y].position.x = x * perlinStep + perlinStep/2 - 1270;
		avatarsMesh[x][y].position.y = y * perlinStep + perlinStep/2 - 1200;
		avatarsMesh[x][y].name = "avatar_" + x + "_" + y;
		scene.addObject(avatarsMesh[x][y]);
	};
	//	Draw the square grid. Right now it is thin cube, change to square later.
	for (var x = 0; x < perlinXSeg; x++) {
		for (var y = 0; y < perlinYSeg; y++) {
			
			if(x == 7 && y == 5){
				var texture = THREE.ImageUtils.loadTexture( "images/head.png", THREE.UVMapping, callbackAvatar(x, y) );
			}
			else{
				var texture = THREE.ImageUtils.loadTexture( "images/female.png", THREE.UVMapping, callbackAvatar(x, y) );
			}
			avatarsTexture[x][y] = texture;
		}
	}

	projector = new THREE.Projector();
	
	renderer = new THREE.WebGLRenderer( { clearColor: 0x000000, clearAlpha: 0, antialias: false } );
//	renderer = new THREE.CanvasRenderer( { clearColor: 0x000000, clearAlpha: 0, antialias: false } );
    renderer.setSize( homeWidth, homeHeight );

    document.body.appendChild( renderer.domElement );
	
	document.addEventListener( 'mousemove', onDocumentMouseMove, false );
	document.addEventListener( 'mousedown', onDocumentMouseDown, false );
	document.addEventListener( 'mouseover', onDocumentMouseOver, false );
	document.addEventListener( 'mouseout', onDocumentMouseOut, false );


}

function onDocumentMouseMove(event) {

	mouseX = event.clientX - homeWidth/2;
	mouseY = event.clientY - homeHeight/2;
	
	var vector = new THREE.Vector3( ( event.clientX / homeWidth ) * 2 - 1, - ( event.clientY / homeHeight ) * 2 + 1, 0.5 );
	projector.unprojectVector( vector, camera );

	var ray = new THREE.Ray( camera.position, vector.subSelf( camera.position ).normalize() );

	var intersects = ray.intersectScene( scene );
	
	//	Only trigger this when mouse enter an Avatar the first time.
	if (intersects.length > 0) {
		currentHoveAvatarName = intersects[ 0 ].object.name;
		if(currentHoveAvatarName != lastHoverAvatarName){
			lastHoverAvatarName = currentHoveAvatarName;
		}					
	}
	else{
		currentHoveAvatarName = "";
	}

}

function onDocumentMouseDown( event ) {

	event.preventDefault();

	var vector = new THREE.Vector3( ( event.clientX / homeWidth ) * 2 - 1, - ( event.clientY / homeHeight ) * 2 + 1, 0.5 );
	projector.unprojectVector( vector, camera );

	var ray = new THREE.Ray( camera.position, vector.subSelf( camera.position ).normalize() );

	var intersects = ray.intersectScene( scene );

	if ( intersects.length > 0 ) {

//					intersects[ 0 ].object.materials[ 0 ].color.setHex( Math.random() * 0xffffff );
		clickedAvatarName = intersects[ 0 ].object.name;

	}

	/*
	// Parse all the faces
	for ( var i in intersects ) {

		intersects[ i ].face.material[ 0 ].color.setHex( Math.random() * 0xffffff | 0x80000000 );

	}
	*/
}


function onDocumentMouseOver(event){
	event.preventDefault();

}

function onDocumentMouseOut(event){
	
}

function animate() {

    // Include examples/js/RequestAnimationFrame.js for cross-browser compatibility.
    requestAnimationFrame( animate );
    render();

}

//	The alter function with the draw() in Processing. It happens every frame.
function render() {
					
	var xoff = 0;
	for (var x = 0; x < perlinXSeg; x++) {
		xoff += perlin3DIncrement;
		var yoff = 0;
		for (var y = 0; y < perlinYSeg; y++) {
			yoff += perlin3DIncrement;
			
			var bright = perlin.noise3d(xoff, yoff, zoff)*255;
			
			if(avatarsMesh[x][y].name == currentHoveAvatarName){
				avatarsMesh[x][y].position.z = bright/1.3-220;
				avatarsMesh[x][y].position.z += hoverTranslate[x][y];
				hoverTranslate[x][y] = (hoverTranslate[x][y] < hoverTranslateThreshold) ? (hoverTranslate[x][y] + hoverTranslateSpeed) : (hoverTranslate[x][y] + 0);
//							hoverTranslateSpeed += hoverTranslateAccerlertion;
			}
			else{
				avatarsMesh[x][y].position.z = bright/1.3-220;
				avatarsMesh[x][y].position.z += hoverTranslate[x][y];
				hoverTranslate[x][y] = (hoverTranslate[x][y] > 0) ? (hoverTranslate[x][y] - hoverTranslateSpeed) : (hoverTranslate[x][y] - 0);

			}
		}
	}
	
	zoff += zincrement;

	//	Move the camera
	if (clickedAvatarName != "") {
		camera.target.position.x += ( scene.getChildByName(clickedAvatarName).position.x - camera.target.position.x ) * .05;
		camera.target.position.y += ( scene.getChildByName(clickedAvatarName).position.y - camera.target.position.y ) * .05;

		camera.position.x += ( scene.getChildByName(clickedAvatarName).position.x - camera.position.x ) * .05;
		camera.position.y += ( scene.getChildByName(clickedAvatarName).position.y - camera.position.y ) * .05;
		camera.position.z -= 50;
		
		if(camera.position.z < scene.getChildByName(clickedAvatarName).position.z){
            isRendering = false;
            if(!isRedirected){
                window.location.href = "avatarChild.php";
                isRedirected = true;
            }
			
		}
		
		camera.updateMatrix();
	}
	else{
		camera.position.x += ( mouseX - camera.position.x ) * .05;
		camera.position.y += ( - mouseY + 200 - camera.position.y ) * .05;
		camera.updateMatrix();
	}
    
    if(isRendering){
        renderer.render( scene, camera );
    }
    else{
        renderer.clear();    
    }

}