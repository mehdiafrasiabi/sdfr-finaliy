@props([
    'color' => '#7dd3fc',   /* رنگ خط نوری */
])

<div class="sdfr-lines" aria-hidden="true" {{ $attributes }}
style="--comet-color: {{ $color }}">
    <span class="comet" style="top:6%;left:-8%;transform:rotate(20deg)"><span class="core" style="--dur:9s;--delay:0s;--dist:1500px"></span></span>
    <span class="comet" style="top:-5%;left:62%;transform:rotate(150deg)"><span class="core" style="--dur:11s;--delay:2.5s;--dist:1500px"></span></span>
    <span class="comet" style="top:70%;left:-10%;transform:rotate(-12deg)"><span class="core" style="--dur:8s;--delay:4s;--dist:1500px"></span></span>
    <span class="comet" style="top:30%;left:82%;transform:rotate(200deg)"><span class="core" style="--dur:12s;--delay:1.2s;--dist:1500px"></span></span>
    <span class="comet" style="top:85%;left:50%;transform:rotate(220deg)"><span class="core" style="--dur:10s;--delay:5.5s;--dist:1500px"></span></span>
    <span class="comet" style="top:15%;left:30%;transform:rotate(35deg)"><span class="core" style="--dur:13s;--delay:3.3s;--dist:1500px"></span></span>
    <span class="comet" style="top:50%;left:-12%;transform:rotate(8deg)"><span class="core" style="--dur:9.5s;--delay:6.8s;--dist:1500px"></span></span>
</div>
