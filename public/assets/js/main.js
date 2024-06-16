import {loadGLTF, loadAudio, loadVideo} from "./loader.js";
const THREE = window.MINDAR.IMAGE.THREE;

document.addEventListener('DOMContentLoaded', () => {
  console.log('Loaded!');
  const start = async() => {
    //initiate the AR 3 object
    const mindarThree = new window.MINDAR.IMAGE.MindARThree({
      container: document.body,
      imageTargetSrc: window.mindFile
    });
    const {renderer, scene, camera} = mindarThree;

    let ar = [];
    let index = 0;
    window.ar = ar;

    for (const media of window.mediaFiles) {
        // const index = window.mediaFiles.indexOf(media.media_file);
        ar['video' + index] = await loadVideo(media.media_file);
        ar['texture' + index] = new THREE.VideoTexture(ar['video' + index]);
        ar['geometry' + index] = new THREE.PlaneGeometry(media.width_aspect, window.height_aspect);
        ar['material' + index] = new THREE.MeshBasicMaterial({map: ar['texture' + index]});
        ar['plane' + index] = new THREE.Mesh(ar['geometry' + index], ar['material' + index]);
        ar['anchor' + index] = mindarThree.addAnchor(index);

        ar['anchor' + index].group.add(ar['plane' + index]);

        console.log('index', ar['video' + index]);

        let functionName = 'playVideo' + index;

        ar['anchor' + index].onTargetFound = functionName;
        ar['anchor' + index].onTargetLost = 'playVideo' + index;

        window[functionName] = function() {
            ar['video' + index].play();
        }

        // function nameFunction(name, body) {
        //     return {[name](...args) {return body.apply(this, args)}}[name]
        // }
        //
        // const x = nameFunction("playVideo" + index, function (index) {
        //     return ar['video' + index].play();
        // });

        // console.log('playVideo' + index);
        // video0();
        // x();
        // console.log(x(9)) // => 18
        // console.log(x.name) // => "wonderful function"

        // let name = "playVideo" + index;
        // let func = new Function(
        //     "return function " + name + "(index){ return window.ar['video' + index].play()}"
        // )(index);
        //
        // func(index);
        //
        // let name1 = "pauseVideo" + index;
        // let func1 = new Function(
        //     "return function " + name1 + "(index){ return window.ar['video' + index].pause()}"
        // )(index);
        //
        // func1(index);

        // function pauseVideo (index){
        //     return ar['video' + index].pause();
        // }
        // async function playVideo(index){
        //     // console.log('index', index);
        //     // console.log('video', ar['video' + index]);
        //     // console.log('geometry', ar['geometry' + index]);
        //     // console.log('video index', 'video' + index);
        //     console.log('ar from inside', ar);
        //     // console.log('video from inside', ar['video0']);
        //     // try {
        //     //     await ar['video' + index].play();
        //     // } catch (e) {
        //     //   console.log('video play failed');
        //     // }
        //
        //
        //     return ar['video' + index].play();
        // }

        // ar['video' + index].play();
        index = index +  1;
    }

    console.log('ar', ar);


//light is needed when we use 3D objects (δεν χρειάζεται το φως)
    //const light = new THREE.HemisphereLight( 0xffffff, 0xbbbbff, 1 );
    //scene.add(light);

     // load and create the first video plane
  // const video1 = await loadVideo(window.mediaFile);
  // const texture1 = new THREE.VideoTexture(video1);
  // const geometry1 = new THREE.PlaneGeometry(window.width_aspect, window.height_aspect);
  // const material1 = new THREE.MeshBasicMaterial({map: texture1});
  // const plane1 = new THREE.Mesh(geometry1, material1);

  // load and create the second video plane
  // const video2 = await loadVideo("./assets/videos/asset.mp4");
  // const texture2 = new THREE.VideoTexture(video2);
  // const geometry2 = new THREE.PlaneGeometry(1, 240/428);
  // const material2 = new THREE.MeshBasicMaterial({map: texture2});
  // const plane2 = new THREE.Mesh(geometry2, material2);
  //
  // // load and create the third video plane
  // const video3 = await loadVideo("./assets/videos/asset.mp4");
  // const texture3 = new THREE.VideoTexture(video3);
  // const geometry3 = new THREE.PlaneGeometry(1, 240/428);
  // const material3 = new THREE.MeshBasicMaterial({map: texture3});
  // const plane3 = new THREE.Mesh(geometry3, material3);

  // add the first video plane to an anchor
  // const anchor1 = mindarThree.addAnchor(0);
  // anchor1.group.add(plane1);
  //
  // anchor1.onTargetFound = () => {
  //   console.log('Start 1 video');
    // video1.play();
  // }
  // anchor1.onTargetLost = () => {
  //   video1.pause();
  // }
  // video1.addEventListener( 'play', () => {
  //   video1.currentTime = 18;
  // });
  //
  // // add the second video plane to an anchor
  // const anchor2 = mindarThree.addAnchor(1);
  // anchor2.group.add(plane2);
  //
  // anchor2.onTargetFound = () => {
  //   //console.log('Video 2 started');
  //   video2.play();
  // }
  // anchor2.onTargetLost = () => {
  //   video2.pause();
  // }
  // // video2.addEventListener( 'play', () => {
  // //   video2.currentTime = 5;
  // // });
  //
  // // add the third video plane to an anchor
  // const anchor3 = mindarThree.addAnchor(2);
  // anchor3.group.add(plane3);
  //
  // anchor3.onTargetFound = () => {
  //   video3.play();
  // }
  // anchor3.onTargetLost = () => {
  //   video3.pause();
  // }
  // video3.addEventListener( 'play', () => {
  //   video3.currentTime = 9;
  // });

//start the experience
    await mindarThree.start();
    renderer.setAnimationLoop(() => {
      renderer.render(scene, camera);
    });
  }

  start();
});
