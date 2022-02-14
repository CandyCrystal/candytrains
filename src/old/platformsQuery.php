<?php

class platformDepartures
{
    private $stationRef;

    function __construct($stationRef)
    {
        $this->station = $stationRef;
    }
    function getPlatformNumber($number)
    {

        return '<p class="platformNumLine1">Spor</p>
        <p class="platformNumLine2">Track</p>
        <p class="platformNumLine3">' . $number . '</p>';
    }

    function getPlatformDisplay($number, $side, $hasSectors)
    {
        if ($side == "right") {
            $sectorLetters = '    <div id="departure_' . $number . '_sectorLetters" class="sectorLetterRow">
            <div class="sectorLetter">H</div>
            <div class="sectorLetter">G</div>
            <div class="sectorLetter">F</div>
            <div class="sectorLetter">E</div>
            <div class="sectorLetter">D</div>
            <div class="sectorLetter">C</div>
            <div class="sectorLetter">B</div>
            <div class="sectorLetter">A</div>';
        } else {
            $sectorLetters = '<div id="departure_' . $number . '_sectorLetters" class="sectorLetterRow">
            <div class="sectorLetter">A</div>
            <div class="sectorLetter">B</div>
            <div class="sectorLetter">C</div>
            <div class="sectorLetter">D</div>
            <div class="sectorLetter">E</div>
            <div class="sectorLetter">F</div>
            <div class="sectorLetter">G</div>
            <div class="sectorLetter">H</div>';
        }
        return '
    <div id="departure_' . $number . '_lineCompany" class="departureImages">
        <img id="departure_' . $number . '_line" class="departureImage" src="./assets/media/lines/centered/L12x.svg">
        <img id="departure_' . $number . '_operator" class="departureImage" src="./assets/media/companies/VY.svg">
    </div>
    <div class="currentTime"></div>

    <div id="departure_' . $number . '_destination" class="departureDestination">Destination</div>
    <div id="departure_' . $number . '_time" class="departureTime">ti:me</div>

    <div id="departure_' . $number . '_remark" class="departureRemarks">Remark</div>
    <div id="departure_' . $number . '_newTime" class="departureNewTime">new:time</div>
    <div id="departure_' . $number . '_newTimeText" class="departureNewTimeText"><b>Ny tid</b> New time</div>
    ' . $sectorLetters .
            '<div id="departure_' . $number . '_trainDisplay" class="trainDisplay" >
        <div id="departure_' . $number . '_trainDisplayTrain" class="trainDisplayTrain"></div></div>
        </div>
        
    <div class="secondDeparture">
        <div id="departure_' . $number . 'B_time" class="time_B">ti:me</div>
        <div id="departure_' . $number . 'B_destination" class="destination_B">Destination</div>
        <img id="departure_' . $number . 'B_line" class="line_B" src="./assets/media/lines/centered/L12x.svg">
        <div id="departure_' . $number . 'B_newTime" class="newTime_B">ti:med</div>
        <div id="departure_' . $number . 'B_newTimeText" class="newTimeText_B"><b>Ny tid</b> New time</div>
    </div>


    <div id="departure_' . $number . '_noBoardingArrival" class="departureNoBoardingText"><b>Ankomst</b> Arrival</div>

    <div id="departure_' . $number . '_noBoardingLine" class="departureNoBoardingLine">
        <img id="departure_' . $number . '_noBoardingLineImage" class="image" src="./assets/media/lines/centered/F2.svg">
    </div>
    <div id="departure_' . $number . '_noBoardingOrigin" class="departureNoBoardingOrigin">Oslo Lufthavn</div>
    <div id="departure_' . $number . '_noBoardingTime" class="departureNoBoardingTime">13:04</div>
    <div id="departure_' . $number . '_noBoardingNewTime" class="departureNoBoardingNewTime">14:58</div>
    <div id="departure_' . $number . '_noBoardingNewTimeText" class="departureNoBoardingNewTimeText"><b>Ny tid</b> New time</div>
    </div>

    <div id="departure_' . $number . '_noBoardingText" class="departureNoBoardingDontBoard"><b>Ingen påstigning</b><br />Please
        do not board
    </div>

    <div id="departure_' . $number . '_cancelledText" class="cancelledText"><b>Innstilt</b> Cancelled</div>

';
    }
}
