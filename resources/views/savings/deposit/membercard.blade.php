<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<style>
		@import url("https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i");
		@import url('https://fonts.googleapis.com/css?family=Overpass+Mono');


		:root {
		  --blue: #0f00ef;
		  --blue2: #4a43b7;
		  //--red: #ce0e1d;
		  //--red2: #ce0e1e;
		  --white: #f6d3fd;
		  --height: 222px;
		  --width: 350px;
		  --font: "Overpass Mono", monospaced;
		  --masterFont: "Raleway", sans-serif;
		}

		.card {
		  --heightCalc: - calc((var(--height) / 2) - 10px);
		  --widthCalc: calc(var(--width) / 2);
		  height: var(--height);
		  width: var(--width);
		  min-width: var(--width);
		  //background: linear-gradient(90deg, var(--purple), var(--purple2));
		  background: linear-gradient(90deg, var(--blue), var(--blue2));
		  border-radius: 10px;
		  box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
		  margin: 10px auto;
		  position: relative;
		  filter: drop-shadow(3px 3px 3px rgba(0, 0, 0, 0.6));
		  transform: translateY(calc(50% - 10px));
		}

		.card__figure {
		  position: relative;
		  height: 40px;
		  margin: 0;
		}

		.card__figure::after {
		  content: "MEMBER CARD";
		  font-size: 0.45em;
		  letter-spacing: 4px;
		  color: var(--white);
		  position: absolute;
		  width: 100%;
		  text-align: center;
		  bottom: calc(-100% - 15px);
		}

		.card__figure--logo {
		  --widthLogo: 140px;
		  width: var(--widthLogo);
		  position: absolute;
		  right: 50%;
		  transform: translate(calc(var(--widthLogo) / 2), 20px);
		}

		.card__reader {
		  width: 50px;
		  height: 40px;
		  background: radial-gradient(#d9a56c, #b18457);
		  border-radius: 5px;
		  position: absolute;
		  top: 50%;
		  transform: translate(33px, -40px);
		  overflow: hidden;
		}

		.card__reader--risk {
		  width: 50px;
		  height: 40px;
		  border-radius: 13px;
		  background: transparent;
		  border: 1px solid #666;
		  position: absolute;
		  z-index: 0;
		}

		.card__reader--risk-one {
		  transform: translate(37px, 15px);
		}

		.card__reader--risk-two {
		  transform: translate(15px, 30px);
		}

		.card__reader--risk-three {
		  transform: translate(-37px, -15px);
		}

		.card__reader--risk-four {
		  transform: translate(-15px, -30px);
		}

		.card__number {
		  font-family: var(--font);
		  font-size: 1.15em;
		  font-weight: normal;
		  color: var(--white);
		  letter-spacing: 2.5px;
		  text-align: center;
		  margin-left: -20px;
		  margin-top: 80px;
		  z-index: 1;
		  position: relative;
		  filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.3));
		}

		.card__number::before,
		.card__number::after {
		  font-family: Arial;
		  color: rgba(0, 0, 0, 0.4);
		  font-size: 0.4em;
		  position: absolute;
		  letter-spacing: 0px;
		}

		.card__number::before {
		  content: "5032";
		  transform: translate(15px, 22px);
		}

		.card__number::after {
		  content: "Valid thru";
		  width: 80px;
		  transform: translate(-215px, 22px);
		}

		.card__dates {
		  position: absolute;
		  width: 40%;
		  font-size: .9em;
		  display: flex;
		  justify-content: space-between;
		  color: var(--white);
		  font-family: var(--font);
		  bottom: 22%;
		  transform: translate(60px, 0px);
		}

		.card__dates span {
		  filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.3));
		}

		.card__name {
		  font-family: var(--font);
		  font-size: .9em;
		  font-weight: normal;
		  color: var(--white);
		  letter-spacing: 2.5px;
		  transform: translate(35px, 25px);
		  position: relative;
		  filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.3));
		}

		.card__flag {
		  right: 30px;
		  top: 45%;
		  border-radius: 50px;
		  transform: translate(0, -28px);
		  position: absolute;
		  height: 110px;
		  width: 110px;
		  border: 1px solid rgba(0, 0, 0, 0.0);
		}

		.card__flag--globe {
		  --margin: 5px;
		  width: calc(100% - (var(--margin) * 2));
		  background-color: #b7b2b8;
		  height: 50%;
		  border-radius: 20px;
		  margin: var(--margin);
		  background: repeating-linear-gradient(
			0deg,
			#dbd1dc,
			#dbd1dc 5px,
			#b2aab5 7px,
			#b2aab5 5px
		  );
		}

		.card__flag--globe::after,
		.card__flag--globe::before {
		  content: "\f0ac";
		  font-family: FontAwesome;
		  color: rgba(0, 0, 0, 0.2);
		  font-size: 3em;
		  top: 6px;
		  left: 2px;
		  position: absolute;
		}

		.card__flag--globe::after {
		  transform: translateX(10px);
		}

		.card__flag--globe::before {
		  transform: translateX(25px);
		}

		.card__flag--red {
		  position: absolute;
		  left: 5px;
		  width: 40px;
		  height: 40px;
		  border-radius: 50%;
		  background-color: #ce0e1d;
		}

		.card__flag--yellow {
		  position: absolute;
		  right: 5px;
		  width: 40px;
		  height: 40px;
		  border-radius: 50%;
		  background-color: #e39833;
		}

		.card__flag--yellow::after {
		  content: "MasterCard";
		  position: absolute;
		  font-size: 0.7em;
		  top: 50%;
		  transform: translate(-25px, -7px);
		  font-family: var(--masterFont);
		  font-style: italic;
		  font-weight: 800;
		  color: var(--white);
		  filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.5));
		}

		.assign {
		  text-align: center;
		  margin-top: 113px;
		  font-family: var(--masterFont);
		  color: #fff;
		  font-size: 0.8em;
		  filter: drop-shadow(1px 1px 1px rgba(0, 0, 0, 0.3));
		}

		.assign a {
		  text-decoration: none;
		  font-weight: 700;
		  color: #FFF;
		}

		.assign a::hover {
		  color: var(--purple);
		}

		</style>
	</head>
	<body>
	@foreach($customers as $customer)
	<div class="card">
		<figure class="card__figure">
			<span class="card__figure--logo" style="color:white;">KSP PARODANA-M</span>
		</figure>
		<div class="card__reader">
			<div class="card__reader--risk card__reader--risk-one"></div>
			<div class="card__reader--risk card__reader--risk-two"></div>
			<div class="card__reader--risk card__reader--risk-three"></div>
			<div class="card__reader--risk card__reader--risk-four"></div>
		</div>
		<p class="card__number">{{$customer->member_number}}</p>
		<!--div class="card__dates">
		  <span class="card__dates--first">09/16</span>
		  <span class="card__dates--second">09/19</span>
		</div-->
		<p class="card__name">{{Str::upper($customer->name)}}<p>
		<div class="card__flag">
			<img src="{{asset('/img/logo/logo-small.png')}}" style="height: 110px; width:110px;"></img>
		</div>
	</div>
	@endforeach
	</body>
</html>