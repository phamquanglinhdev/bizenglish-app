<html>

<head>
    <title>Lớp học :{{$grade->name}}</title>
</head>


<body>
<div id="root"></div>
</body>
<script src="https://unpkg.com/@zegocloud/zego-uikit-prebuilt/zego-uikit-prebuilt.js"></script>
<script>
    document.addEventListener("contextmenu",
        event => event.preventDefault()
    );
    window.onload = function () {
        function getUrlParams(url) {
            let urlStr = url.split('?')[1];
            const urlSearchParams = new URLSearchParams(urlStr);
            return Object.fromEntries(urlSearchParams.entries());
        }


        // Generate a Token by calling a method.
        // @param 1: appID
        // @param 2: serverSecret
        // @param 3: Room ID
        // @param 4: User ID
        // @param 5: Username
        const roomID = getUrlParams(window.location.href)['roomID'] || "{{$grade->id}}";
        const userID = Math.floor(Math.random() * 10000) + "";
        const userName = "{{\Illuminate\Support\Str::slug(backpack_user()->name," ","en")}}";
        const appID = 136078041;
        const serverSecret = "74eb720682e208249808cbb6ca79cdf4";
        const kitToken = ZegoUIKitPrebuilt.generateKitTokenForTest(appID, serverSecret, roomID, userID, userName);

        const role =ZegoUIKitPrebuilt.Host;
        const zp = ZegoUIKitPrebuilt.create(kitToken);
        zp.joinRoom({
            container: document.querySelector("#root"),
            // sharedLinks: [{
            //     name: 'Chia sẻ link',
            //     url: window.location.protocol + '//' + window.location.host  + window.location.pathname + '?roomID=' + roomID,
            // }],
            scenario: {
                mode: ZegoUIKitPrebuilt.VideoConference,
            },
            config:{
                role
            },
            onUserAvatarSetter: (userList) => {
                userList.forEach(user => {
                    user.setUserAvatar("{{backpack_user()->avatar}}")
                })
            },
            onLeaveRoom: () => {
                window.history.back()
            },
            turnOnMicrophoneWhenJoining: false,
            turnOnCameraWhenJoining: false,
            showMyCameraToggleButton: true,
            showMyMicrophoneToggleButton: true,
            showAudioVideoSettingsButton: true,
            showScreenSharingButton: true,
            showTextChat: true,
            showUserList: true,
            maxUsers: 50,
            layout: "Auto",
            showLayoutButton: true,
            showPreJoinView: false,
            branding: {logoURL: "https://files.catbox.moe/no4qdd.png"},
            showPinButton: true,
            recording: true,
        });
    }
</script>
<style>
    .tQCB5Tj6hsWzMNzTg5og {
        background: red !important;
    }
</style>
<script>

</script>
</html>
