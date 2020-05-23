<!DOCTYPE html>
<html>

<head>
    <title>{{ config('app.name', 'ZipFile') }} :: 403</title>
</head>
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<style type="text/css">
   @import url('https://fonts.googleapis.com/css?family=Roboto|VT323');
    /** Styles for the 403 Page **/

    .particle-error,
    .permission_denied,
    #particles-js {
        width: 100%;
        height: 100%;
        margin: 0px !important;
    }

    #particles-js {
        position: fixed !important;
        opacity: 0.23;
    }

    .permission_denied {
        background: #24344C !important;
    }

    .permission_denied a {
        text-decoration: none;
    }

    .denied__wrapper {
        max-width: 320px;
        width: 100%;
        height: 320px;
        display: block;
        margin: 0 auto;
        position: relative;
        margin-top: 10vh;
        padding: 10px;
    }

    .permission_denied h1 {
        text-align: center;
        color: #fff;
        font-family: 'VT323', sans-serif;
        font-size: 100px;
        margin-bottom: 0px;
        font-weight: 800;
    }

    .permission_denied h3 {
        text-align: center;
        color: #fff;
        font-size: 19px;
        line-height: 23px;
        max-width: 330px;
        margin: 0px auto 30px auto;
        font-family: 'VT323', sans-serif;
        font-weight: 400;
    }

    .permission_denied h3 span {
        position: relative;
        width: 65px;
        display: inline-block;
    }

    .permission_denied h3 span:after {
        content: '';
        border-bottom: 3px solid #FFBB39;
        position: absolute;
        left: 0;
        top: 43%;
        width: 100%;
    }

    .denied__link {
        background: none;
        color: #fff;
        padding: 12px 0px 10px 0px;
        border: 1px solid #fff;
        ;
        outline: none;
        border-radius: 7px;
        width: 150px;
        font-size: 15px;
        text-align: center;
        margin: 0 auto;
        vertical-align: middle;
        display: block;
        margin-bottom: 40px;
        margin-top: 25px;
        font-family: 'VT323', sans-serif;
        font-weight: 400;
    }

    .denied__link:hover {
        color: #FFBB39;
        border-color: #FFBB39;
        cursor: pointer;
        opacity: 1.0;
    }

    .permission_denied .stars {
        animation: sparkle 1.6s infinite ease-in-out alternate;
    }

    .icon-credits {
        position: fixed;
        bottom: 37px;
        right: 0;
        font-size: 10px;
        color: #888;
        margin-right: 10px;
        font-family: 'Roboto', sans-serif;
    }

    .icon-credits a {
        color: #888;
    }

    @keyframes sparkle {
        0% {
            opacity: 1.0;
        }
        100% {
            opacity: 0.3;
        }
    }

    #astronaut {
        width: 43px;
        position: absolute;
        right: 0px;
        top: 200px;
        animation: spin 4.5s infinite linear;
    }

    @keyframes spin {
        0% {
            transform: rotateZ(0deg);
        }
        100% {
            transform: rotateZ(360deg);
        }
    }

    @media (max-width: 600px) {
        .permission_denied h1 {
            font-size: 75px;
        }
        .permission_denied h3 {
            font-size: 16px;
            width: 200px;
            margin: 0 auto;
            line-height: 23px;
        }
        .permission_denied h3 span {
            width: 60px;
        }
        #astronaut {
            width: 35px;
            right: 40px;
            top: 170px;
        }
    }

    .saturn,
    .saturn-2,
    .hover {
        animation: hover 2s infinite ease-in-out alternate;
    }

    @keyframes hover {
        0% {
            transform: translateY(3px);
        }
        100% {
            transform: translateY(-3px);
        }
    }
</style>

<!-- Icons purchased via Iconfinder under Basic License. Don't steal. Purchase your own if you want to use -->

<body class="permission_denied">
    <div id="particles-js"></div>
    <div class="denied__wrapper">
        <h1>403</h1>
        <h3>Houston, we have a problem, looks like you don't have enough fuel to land...</h3>

        <svg id="astronaut" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
            <path style="fill:#E2395A;" d="M460.914,495.393c-6.783-62.938-33.338-110.975-54.419-140.19
	c-22.923-31.767-44.345-48.705-45.245-49.411c-4.22-3.305-9.882-4.106-14.854-2.105c-4.972,2.003-8.497,6.507-9.248,11.814
	c-6.921,48.951-23.416,88.2-53.484,127.261c-3.011,3.911-3.904,9.053-2.39,13.751c1.514,4.697,5.242,8.35,9.97,9.767l150.45,45.09
	c4.741,1.422,9.974,0.426,13.895-2.833C459.453,505.323,461.452,500.389,460.914,495.393z" />
            <path style="fill:#FC495C;" d="M228.337,442.761c-30.068-39.061-46.563-78.31-53.484-127.261c-0.751-5.308-4.276-9.812-9.248-11.814
	c-4.973-2.002-10.635-1.2-14.854,2.105c-0.9,0.706-22.322,17.645-45.245,49.412c-21.081,29.215-47.636,77.252-54.419,140.189
	c-0.538,4.996,1.461,9.931,5.326,13.143c3.91,3.251,9.142,4.26,13.895,2.833l150.45-45.09c4.728-1.417,8.456-5.069,9.97-9.767
	C232.241,451.813,231.348,446.672,228.337,442.761z" />
            <path style="fill:#FD6B82;" d="M371,256c0,58.94-8.9,105.7-28.02,147.15c-18.67,40.48-45.38,71.99-71.98,99.88
	c-1.4,1.47-2.81,2.94-4.21,4.39c-2.82,2.93-6.72,4.58-10.79,4.58s-7.97-1.66-10.79-4.58c-1.43-1.48-2.83-2.94-4.21-4.39
	c-14.73-15.48-26.4-28.92-36.44-41.97c-33.4-43.4-51.73-86.99-59.41-141.36c-2.79-19.69-4.15-40.53-4.15-63.7
	c0-92.49,37.01-181.78,104.21-251.42C248.03,1.65,251.93,0,256,0s7.97,1.65,10.79,4.58C333.99,74.22,371,163.51,371,256z" />
            <path style="fill:#FC495C;" d="M266.79,4.58C263.97,1.65,260.07,0,256,0v512c4.07,0,7.97-1.65,10.79-4.58
	c1.4-1.45,2.81-2.92,4.21-4.39c26.6-27.89,53.31-59.4,71.98-99.88C362.1,361.7,371,314.94,371,256
	C371,163.51,333.99,74.22,266.79,4.58z" />
            <path style="fill:#FAD557;" d="M286.82,192.167c-7.303-10.994-18.536-17.3-30.82-17.3s-23.518,6.306-30.82,17.3
	c-4.585,6.9-13.895,8.778-20.795,4.195c-6.9-4.584-8.778-13.894-4.195-20.795c12.77-19.224,33.633-30.7,55.81-30.7
	c22.177,0,43.041,11.477,55.81,30.7c4.583,6.901,2.705,16.211-4.195,20.795C300.74,200.928,291.423,199.095,286.82,192.167z" />
            <path style="fill:#FC495C;" d="M271,327v176.03c-1.4,1.47-2.81,2.94-4.21,4.39c-2.82,2.93-6.72,4.58-10.79,4.58
	s-7.97-1.66-10.79-4.58c-1.43-1.48-2.83-2.94-4.21-4.39V327c0-8.28,6.72-15,15-15S271,318.72,271,327z" />
            <path style="fill:#FCB12B;" d="M311.811,175.567c-12.77-19.224-33.633-30.7-55.811-30.7v30c12.284,0,23.518,6.306,30.82,17.3
	c4.603,6.928,13.92,8.761,20.795,4.195C314.516,191.778,316.394,182.469,311.811,175.567z" />
            <path style="fill:#E2395A;" d="M256,312v200c4.07,0,7.97-1.65,10.79-4.58c1.4-1.45,2.81-2.92,4.21-4.39V327
	C271,318.72,264.28,312,256,312z" />
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
        </svg>

        <svg id="planet" viewBox="0 0 512 512" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" style="enable-background:new 0 0 511.995 511.995;" xml:space="preserve">
            <circle style="fill:#4398D1;" cx="255.988" cy="255.984" r="255.982" />
            <path style="fill:#3E8CC7;" d="M376.132,29.943c66.37,124.831,18.978,279.824-105.844,346.194
	c-75.135,39.951-165.215,39.951-240.35,0c66.37,124.831,221.363,172.214,346.194,105.844s172.214-221.363,105.844-346.194
	C458.028,90.752,421.176,53.891,376.132,29.943z" />
            <g>
                <path style="fill:#88B337;" d="M282.478,70.636l65.408-53.58C299.628-1.499,246.878-5.012,196.591,6.993l50.579,28.335
		L282.478,70.636z" />
                <path style="fill:#88B337;" d="M374.366,84.67l-83.062,83.062l44.135,52.962h70.616v141.232l62.318-38.927
		c5.173-3.231,8.306-8.906,8.297-15.006v-34.337c0-5.561,2.613-10.787,7.062-14.123l27.54-21.185
		c-3.999-59.511-28.741-115.73-69.91-158.886h-54.462C382.196,79.462,377.685,81.334,374.366,84.67z" />
                <path style="fill:#88B337;" d="M70.63,432.542c42.493,44.726,99.754,72.54,161.181,78.295l-90.565-78.295H70.63z" />
                <path style="fill:#88B337;" d="M167.727,256.002v132.405l52.962,44.135v-78.295c0.071-6.241,3.425-11.978,8.827-15.094
		l79.443-47.842l-97.097-105.924v-44.135h44.135l-38.839-54.374c-3.257-4.581-8.5-7.335-14.123-7.415H70.63
		c-23.974,25.086-42.59,54.789-54.727,87.299l63.554,89.241L167.727,256.002L167.727,256.002z" />
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
            <g>
            </g>
        </svg>

    </div>

    <div class="icon-credits">
        <div>
            Designed by : <a href="https://codepen.io/adriftinadream/pen/weMpgE" title="Adrift in a Dream">Adrift in a Dream</a>
        </div>
        <div>Icons made by <a href="https://www.flaticon.com/authors/freepik" title="freepik">freepik</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
    </div>

</body>

<script>
    var particles = {
        "particles": {
            "number": {
                "value": 260,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#ffffff"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                },
                "image": {
                    "src": "img/github.svg",
                    "width": 100,
                    "height": 100
                }
            },
            "opacity": {
                "value": 1,
                "random": true,
                "anim": {
                    "enable": true,
                    "speed": 1,
                    "opacity_min": 0,
                    "sync": false
                }
            },
            "size": {
                "value": 3,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 4,
                    "size_min": 0.3,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": false,
                "distance": 150,
                "color": "#ffffff",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 0.01,
                "direction": "none",
                "random": true,
                "straight": false,
                "out_mode": "out",
                "bounce": true,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 600
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": false,
                    "mode": "bubble"
                },
                "onclick": {
                    "enable": false,
                    "mode": "repulse"
                },
                "resize": false
            },
            "modes": {
                "grab": {
                    "distance": 400,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 250,
                    "size": 0,
                    "duration": 2,
                    "opacity": 0,
                    "speed": 3
                },
                "repulse": {
                    "distance": 400,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    };
    particlesJS('particles-js', particles, function() {
        console.log('callback - particles.js config loaded');
    });
</script>

</html>