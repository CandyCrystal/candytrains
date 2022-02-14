<?php class getCustomLine
{
    function __construct()
    {
        // $this->databaseConnection = $conn;
    }
    function getCustomLine($type, $trainNum, $stopString)
    {
        // $L1x = ["LLSSDASTNFJEHABLØRHØBHGAGRONYLALABROSLNTHSKØLYSSTBHVKBLOSVSLEBSTHVAVAKHØNASRBONGHAHEGRØYSPI", "SPIRØYHEGGHABONASRHØNVAKHVABSTSLESVBLOHVKSTBLYSSKØNTHOSLBRALANYLGROHGAHØBLØRHABFJESTNSDALLS"];
        // $R10x = ["LHMMLVBRDHMRSTGTANEVLGARLLSOSLNTHSKØLYSSVASRDRM", "DRMASRSVLYSSKØNTHOSLLLSGAREVLTANSTGHMRBRDMLVLHM"];
        // $R11x = ["SKNPGLVKSFJTORSKKTBGSKPHSDSNDDRMASRSVLYSSKØNTHOSLLLSGAREVVEVL", "EVLEVVGARLLSOSLNTHSKØLYSSVASRDRMSNDHSDSKPTBGSKKTORSFJLVKPGSKN"];
        // $L12x = ["KBGDARVFSHOKSBGMJDGULDRMASRSVLYSSKØNTHOSLLLSGAREVVEVL", "EVLEVVGARLLSOSLNTHSKØLYSSVASRDRMGULMJDSBGHOKVFSDARKBG"];
        // $L13x = ["DRMBRALIEASRSVLYSSKØNTHOSLLLSLSDFROLBGKLØJEHNBYHSRDAL", "DALHSRNBYJEHKLØLBGFROLSDLLSOSLNTHSKØLYSSVASRLIEBRADRM"];
        // $L14x = ["KVGSKAÅRNHAGAULRFSBLKSØRSVIFETNERLLSOSLNTHSKØLYSSVASR", "ASRSVLYSSKØNTHOSLLLSNERFETSVISØRBLKRFSAULHAGÅRNSKAKVG"];
        // $R15x = [];

        // $L2x = ["STBLYSSKØNTHOSLNSTLJAHTOHMARSHKOLSOLMYVGUDOPGVEVLANSKI", "SKILANVEVOPGGUDMYVSOLKOLRSHHMAHTOLJANSTOSLNTHSKØLYSSTB"];
        // $R20x = ["HLDSBOFRERÅDRYGMOSSKIOSL", "OSLSKIMOSRYGRÅDFRESBOHLD"];
        // $L21x = ["MOSKAMSONVBYÅSSKIHMAOSLNTHSKØLYSSTB", "STBLYSSKØNTHOSLHMASKIÅSVBYSONKAMMOS"];
        // $L22x = ["SKØNTHOSLKOLSKIKRÅSBUTOMKNASPGASMSLUMYS", "MYSSLUASMSPGKNATOMSBUKRÅSKIKOLOSLNTHSKØ"];

        // $L3x = ["JARGRALUROAGRUHSTHAKVARÅBYNITMVTSNIKJENYDGRETØYOSL", "OSLTØYGRENYDKJESNIMVTNITÅBYVARHAKHSTGRUROALUGRAJAR"];
        // $R30x = ["OSLGRENYDKJENITHSTGRUROALUGRAJARBLEEINRVORAUGJØ", "GJØRAURVOEINBLEJARGRALUROAGRUHSTNITKJENYDGREOSL"];

        $L1x = ["SPIRØYHEGGHABONASRHØNVAKHVABSTSLESVBLOHVKSTBLYSSKØNTHOSLBRALANYLGROHGAHØBLØRHABFJESTNSDALLS", "LLSSDASTNFJEHABLØRHØBHGAGRONYLALABROSLNTHSKØLYSSTBHVKBLOSVSLEBSTHVAVAKHØNASRBONGHAHEGRØYSPI"];
        $R10x = ["LHMMLVBRDHMRSTGTANEVLGARLLSOSLNTHSKØLYSSVASRDRM", "DRMASRSVLYSSKØNTHOSLLLSGAREVLTANSTGHMRBRDMLVLHM"];
        $R11x = ["EVLEVVGARLLSOSLNTHSKØLYSSVASRDRMSNDHSDSKPTBGSKKTORSFJLVKPGSKN", "SKNPGLVKSFJTORSKKTBGSKPHSDSNDDRMASRSVLYSSKØNTHOSLLLSGAREVVEVL"];
        $L12x = ["KBGDARVFSHOKSBGMJDGULDRMASRSVLYSSKØNTHOSLLLSGAREVVEVL", "EVLEVVGARLLSOSLNTHSKØLYSSVASRDRMGULMJDSBGHOKVFSDARKBG"];
        $L13x = ["DALHSRNBYJEHKLØLBGFROLSDLLSOSLNTHSKØLYSSVASRLIEBRADRM", "DRMBRALIEASRSVLYSSKØNTHOSLLLSLSDFROLBGKLØJEHNBYHSRDAL",];
        $L14x = ["KVGSKAÅRNHAGAULRFSBLKSØRSVIFETNERLLSOSLNTHSKØLYSSVASR", "ASRSVLYSSKØNTHOSLLLSNERFETSVISØRBLKRFSAULHAGÅRNSKAKVG",];
        $R15x = [];

        $L2x = ["SKILANVEVOPGGUDMYVSOLKOLRSHHMAHTOLJANSTOSLNTHSKØLYSSTB", "STBLYSSKØNTHOSLNSTLJAHTOHMARSHKOLSOLMYVGUDOPGVEVLANSKI"];
        $R20x = ["HLDSBOFRERÅDRYGMOSSKIOSL", "OSLSKIMOSRYGRÅDFRESBOHLD"];
        $L21x = ["MOSKAMSONVBYÅSSKIHMAOSLNTHSKØLYSSTB", "STBLYSSKØNTHOSLHMASKIÅSVBYSONKAMMOS",];
        $L22x = ["MYSSLUASMSPGKNATOMSBUKRÅSKIKOLOSLNTHSKØ", "SKØNTHOSLKOLSKIKRÅSBUTOMKNASPGASMSLUMYS"];

        $L3x = ["JARGRALUROAGRUHSTHAKVARÅBYNITMVTSNIKJENYDGRETØYOSL", "OSLTØYGRENYDKJESNIMVTNITÅBYVARHAKHSTGRUROALUGRAJAR"];
        $R30x = ["GJØRAURVOEINBLEJARGRALUROAGRUHSTNITKJENYDGREOSL", "OSLGRENYDKJENITHSTGRUROALUGRAJARBLEEINRVORAUGJØ"];

        $L4x = ["BRGARN", "ARNBRG"];
        $R40x = ["BRGARNDLBOLVOSMYRHALFINHAUUSTGLOÅLGOLNESFLÅHFSVKSHOKDRMASRSVOSL", "BRGARNSTHDLVOSRMGMYRFINUSTGLOÅLGOLNESHFSSBGDRMASRSVOSL", "BRGARNTRDBOLVOSMFJMYRFINHAUUSTGLOÅLGOLHFSDRMASRSVOSL", "BRGARNTRDBOLVOSMFJMYRFINHAUUSTGLOÅLGOLHFSGRUMVTOSL", "BRGARNTRDBOLVOSMYRFINUSTGLOÅLGOLNESFLÅHFSJEVGRUSYHAKOSL", "BRGARNTRDBOLVOSMYRFINUSTGLOÅLGOLNESFLÅHFSVKSDRMASRSVOSL", "BRGARNVOSMYRFINUSTGLOÅLGOLNESFLÅHFSHOKDRMASRSVOSL", "BRGARNVOSMYRHALFINUSTGLOÅLGOLNESHFSHOKMJDDRMASRSVOSL", "BRGARNVOSMYRHALFINUSTGLOÅLGOLNESHFSROAHAKÅBYOSL", "OSLSVASRDRMHFSNESGOLÅLGLOUSTFINHALMYRVOSTRDARNBRG", "OSLSVASRDRMHFSNESGOLÅLGLOUSTFINMYRVOSBOLDLARNBRG", "OSLSVASRDRMHOKHFSFLÅNESGOLÅLGLOUSTHAUFINMYRRMGVOSDLVADARNBRG", "OSLSVASRDRMHOKVKSHFSFLÅNESGOLÅLGLOUSTHAUFINHALMYRVOSDLARNBRG", "OSLSVASRDRMHOKVKSHFSNESGOLÅLGLOUSTHAUFINHALMYRVOSARNBRG", "OSLSVASRDRMVKSHFSNESGOLÅLGLOUSTHAUFINMYRVOSDLVADARNBRG"];
        $R41x = ["BRGARNTRDVADSTHDLBOLEVGBULVOS", "VOSBULEVGBOLDLSTHVADTRDARNBRG"];
        $L42x = ["FMLNDHÅRBERBLHKJFRNUVTHMYR", "MYRVTHRNUKJFBLHBERHÅRLNDFM"];

        $L5x = ["EGSHEVSVÅOGNBRSVIGVHGNBØBRYKLPØKPGANSASSSEGAUJÅTMROPARSTV", "STVPARMROJÅTGAUSSESASGANØKPKLPBRYNBØVHGVIGBRSOGNSVÅHEVEGS"];
        $R50x = ["OSLLYSASRDRMHOKKBGNGUBØLUNDRDNVTGJEVGHNELVNLKRSNDLMDLAUDSNASTOGYLSIRMOIEGSOGNVHGBRYKLPSSEJÅTSTV", "OSLLYSASRDRMKBGBØDRDNVTNELVNLKRSNDLMDLAUDSNASTOGYLSIRMOIEGSOGNNBØBRYKLPSSEJÅTSTV", "OSLLYSASRDRMKBGNGUBØLUNDRDNVTGJENELVNLKRSNDLMDLAUDSNASTOGYLSIRMOIEGSBRYSSEJÅTSTV", "OSLLYSASRDRMKBGNGUBØLUNDRDNVTGJEVGHNELVNLKRSNDLBRLMDLAUDSNASTOGYLSIRMOIEGSOGNNBØBRYSSEJÅTSTV", "OSLLYSASRDRMKBGNGUBØLUNDRDNVTGJEVGHNELVNLKRSNDLBRLMDLAUDSNASTOGYLSIRMOIEGSVHGBRYKLPSSEJÅTSTV", "OSLLYSASRDRMKBGNGUBØLUNDRDNVTGJEVGHNELVNLKRSNDLMDLAUDSNASTOGYLSIRMOIEGSOGNVIGVHGNBØBRYSSEJÅTSTV", "OSLLYSASRDRMKBGNGUBØLUNDRDNVTGJEVGHNELVNLKRSNDLMDLAUDSNASTOGYLSIRMOIEGSVHGBRYKLPSSEJÅTSTV", "STVJÅTSSEBRYEGSMOISIRGYLSTOSNAAUDMDLNDLKRSVNLNELVGHGJENVTDRDLUNBØNGUKBGDRMASRLYSOSL", "STVJÅTSSEGANBRYEGSMOISIRGYLSTOSNAAUDMDLNDLKRSVNLNELVGHGJENVTDRDBØNGUKBGDRMASRLYSOSL", "STVJÅTSSEGANBRYEGSMOISIRGYLSTOSNAAUDMDLNDLKRSVNLNELVGHGJENVTDRDLUNBØNGUKBGDRMASRLYSOSL", "STVJÅTSSEGANBRYEGSMOISIRGYLSTOSNAAUDMDLNDLKRSVNLNELVGHGJENVTDRDLUNBØNGUKBGHOKGULDRMASRLYSOSL", "STVJÅTSSEGANBRYHEVEGSMOISIRGYLSTOSNAAUDMDLNDLKRSVNLNELVGHGJEDRDBØNGUKBGDRMASRLYSOSL", "STVJÅTSSEGANBRYNBØOGNHEVEGSMOISIRGYLSTOSNAAUDMDLNDLKRSVNLNELVGHGJENVTDRDLUNBØNGUKBGDRMASRLYSOSL", "STVJÅTSSEGANBRYOGNEGSMOISIRGYLSTOSNAAUDMDLNDLKRSVNLNELBØNGUKBGMJDGULDRMASRLYSOSL"];
        $L51x = ["NTDTRYNGUNTRSKNPG", "PGSKNNTRNGUTRYNTD"];
        $L52x = ["ADLSOABÅSRISBLAFRLBØYFLANEL", "NELFLABØYFRLBLARISBÅSSOAADL"];

        $L6x = ["STKSPBRØVDLBEGEBELEVSGNRLAÅSESKVSTJVÆRHELHMVVHRRHMROTLEALDMNNNTNDSKSSVNHMDMSKKVÅLERLMO", "LMOLERKVÅMSKHMDSVNSKSTNDNNNLDMLEAROTRHMVHRHMVHELVÆRSTJSKVÅSERLASGNLEVEBEBEGVDLRØSPBSTK"];
        $R60x = ["OSLLLSGARHMRLHMHSSRBUVINOTADOMHJNKVLOPDBÅKSTØHMDTND", "TNDHMDSTØBÅKOPDKVLHJNDOMOTAVINRBUHSSLHMHMRGARLLSOSL"];
        $R61x = ["HMRILSLØTELVRENSNVOPHEVESTAKOPATNHANBMOALVAUMTYNTOLOSROSGOSREIÅLNHDNLLESINKOTRGNSTØHOILMOLERKVÅMSKHMDSLBSVNSKSTND", "HMRILSLØTELVRENSNVOPHEVESTAKOPATNHANBMOALVAUMTYNTOLOSROS", "ROSGOSREIÅLNHDNLLESINKOTRGNSTØHOILMOLERKVÅMSKHMDSLBSVNSKSTND"];
        $L62x = ["ÅNDBJOLSVLESDOM", "DOMLESLSVBJOÅND"];

        $L7x = ["TNDVHRHMVHELMER", "MERHELHMVVHRLEATND"];
        $R70x = ["BOMØRTVLOTRVALFAUROGRØKLØNDUNMOBJEDVTMSJTROMAJNSKLSMHARGRGSNÅJØRSTKVDLBEGLEVÅSESTJVÆRTND", "BOMØRTVLOTRVALFAUROGRØKLØNMOBJEMSJTRONSKGRGSNÅSTKVDLLEVRLAÅSESTJVÆRHELTND", "TNDLEAVÆRSTJRLALEVVDLSTKJØRSNÅGRGHARLSMNSKMAJTROMSJDVTBJEMODUNLØNRØKROGFAUVALOTRTVLMØRBO", "TNDVÆRSTJRLALEVVDLSTKJØRSNÅGRGHARLSMNSKMAJTROMSJDVTBJEMODUNLØNRØKROGFAUVALOTRTVLMØRBO", "TNDVHRVÆRSTJLEVVDLSTKSNÅGRGHARNSKTROMSJBJEMOLØNRØKROGFAUVALOTRTVLMØRBO"];
        $L71x = ["ROGFAUVALOTRTVLMØRBO", "BOMØRTVLOTRVALFAUROG"];

        $L8x = ["BJFKATNK", "NKKATBJF"];
        $R80x = ["BJFKATNK", "NKKATBJF"];

        if ($trainNum >= 3500 && $trainNum <= 3649 || $trainNum >= 3880 && $trainNum <= 3999) {
            $customLine = "FLY2";
        } else if ($trainNum >= 3700 && $trainNum <= 3879) {
            $customLine = "FLY1";
        } else if ($trainNum >= 2100 && $trainNum <= 2299) {
            $customLine = "L1";
            if (!in_array($stopString, $L1x)) {
                $customLine = "L1x";
            }
        } else if ($trainNum >= 300 && $trainNum <= 369 || $trainNum >= 90300 && $trainNum <= 90369) {
            $customLine = "R10";
            if (!in_array($stopString, $R10x)) {
                $customLine = "R10x";
            }
        } else if ($trainNum >= 800 && $trainNum <= 899 || $trainNum >= 80800 && $trainNum <= 80899 || $trainNum >= 90800 && $trainNum <= 90899) {
            $customLine = "R11";
            if (!in_array($stopString, $R11x)) {
                $customLine = "R11x";
            }
        } else if ($trainNum >= 500 && $trainNum <= 599 || $trainNum >= 80500 && $trainNum <= 80599) {
            $customLine = "L12";
            if (!in_array($stopString, $L12x)) {
                $customLine = "L12x";
            }
        } else if ($trainNum >= 1600 && $trainNum <= 1699) {
            $customLine = "L13";
            if (!in_array($stopString, $L13x)) {
                $customLine = "L13x";
            }
        } else if ($trainNum >= 1000 && $trainNum <= 1099) {
            $customLine = "L14";
            if (!in_array($stopString, $L14x)) {
                $customLine = "L14x";
            }
        } else if ($trainNum >= 2700 && $trainNum <= 2899) {
            $customLine = "L2";
            if (!in_array($stopString, $L2x)) {
                $customLine = "L2x";
            }
        } else if ($trainNum >= 100 && $trainNum <= 199 || $trainNum >= 390 && $trainNum <= 399) {
            $customLine = "R20";
            if (!in_array($stopString, $R20x)) {
                $customLine = "R20x";
            }
        } else if ($trainNum >= 1100 && $trainNum <= 1199) {
            $customLine = "L21";
            if (!in_array($stopString, $L21x)) {
                $customLine = "L21x";
            }
        } else if ($trainNum >= 1900 && $trainNum <= 1999) {
            $customLine = "L22";
            if (!in_array($stopString, $L22x)) {
                $customLine = "L22x";
            }
        } else if ($trainNum >= 200 && $trainNum <= 229 || $trainNum == 231 || $trainNum == 238 || $trainNum == 240 || $trainNum >= 259 && $trainNum <= 261 || $trainNum == 266 || $trainNum == 268 || $trainNum >= 293 && $trainNum <= 299 || $trainNum >= 1300 && $trainNum <= 1399) {
            $customLine = "R30";
            if (!in_array($stopString, $R30x)) {
                $customLine = "R30x";
            }
        } else if ($trainNum >= 200 && $trainNum <= 299) {
            $customLine = "L3";
            if (!in_array($stopString, $L3x)) {
                $customLine = "L3x";
            }
        } else if ($trainNum >= 620 && $trainNum <= 689) {
            $customLine = "R15";
            if (!in_array($stopString, $R15x)) {
                $customLine = "R15x";
            }
        } else if ($trainNum >= 2600 && $trainNum <= 2699) {
            // bergen-arna
            $customLine = "L4";
            if (!in_array($stopString, $L4x)) {
                $customLine = "L4x";
            }
        } else if ($trainNum >= 60 && $trainNum <= 69 || $trainNum >= 601 && $trainNum <= 610 || $trainNum >= 80060 && $trainNum <= 80069 || $trainNum >= 80601 && $trainNum <= 80610) {
            // Oslo S-Bergen
            $customLine = "R40";
            if (!in_array($stopString, $R40x)) {
                $customLine = "R40x";
            }
        } else if ($trainNum >= 1801 && $trainNum <= 1848) {
            // bergen-voss
            $customLine = "R41";
            if (!in_array($stopString, $R41x)) {
                $customLine = "R41x";
            }
        } else if ($trainNum >= 1850 && $trainNum <= 1899) {
            //flåmsbana
            $customLine = "L42";
            if (!in_array($stopString, $L42x)) {
                $customLine = "L42x";
            }
        } else if ($trainNum >= 3000 && $trainNum <= 3200) {
            // Jærbanen
            $customLine = "L5";
            if (!in_array($stopString, $L5x)) {
                $customLine = "L5x";
            }
        } else if ($trainNum >= 701 && $trainNum <= 726 || $trainNum >= 2990 && $trainNum <= 2999) {
            // oslo s-stavanger
            $customLine = "R50";
            if (!in_array($stopString, $R50x)) {
                $customLine = "R50x";
            }
        } else if ($trainNum >= 2570 && $trainNum <= 2589) {
            // Notodden-porsgrunn
            $customLine = "L51";
            if (!in_array($stopString, $L51x)) {
                $customLine = "L51x";
            }
        } else if ($trainNum >= 2060 && $trainNum <= 2099) {
            // nelaug-arendal
            $customLine = "L52";
            if (!in_array($stopString, $L52x)) {
                $customLine = "L52x";
            }
        } else if ($trainNum >= 420 && $trainNum <= 469 || $trainNum >= 1700 && $trainNum <= 1769) {
            // Trønderbanen
            $customLine = "L6";
            if (!in_array($stopString, $L6x)) {
                $customLine = "L6x";
            }
        } else if ($trainNum >= 40 && $trainNum <= 59 || $trainNum >= 405 && $trainNum <= 406) {
            // Oslo S-Trondheim
            $customLine = "R60";
            if (!in_array($stopString, $R60x)) {
                $customLine = "R60x";
            }
        } else if ($trainNum >= 407 && $trainNum <= 418 || $trainNum >= 2350 && $trainNum <= 2399 || $trainNum == 1439) {
            // Hamar-Trondheim
            $customLine = "R61";
            if (!in_array($stopString, $R61x)) {
                $customLine = "R61x";
            }
        } else if ($trainNum >= 2340 && $trainNum <= 2349) {
            // Raumabanen
            $customLine = "L62";
            if (!in_array($stopString, $L62x)) {
                $customLine = "L62x";
            }
        } else if ($trainNum >= 1770 && $trainNum <= 1779) {
            // Meråkerbanen
            $customLine = "L7";
            if (!in_array($stopString, $L7x)) {
                $customLine = "L7x";
            }
        } else if ($trainNum >= 470 && $trainNum <= 499 || $trainNum >= 90470 && $trainNum <= 90499) {
            // Nordlandsbanen
            $customLine = "R70";
            if (!in_array($stopString, $R70x)) {
                $customLine = "R70x";
            }
        } else if ($trainNum >= 1780 && $trainNum <= 1799) {
            // Bodø-Rognan
            $customLine = "L71";
            if (!in_array($stopString, $L71x)) {
                $customLine = "L71x";
            }
        } else if ($trainNum >= 3400 && $trainNum <= 3499) {
            // Ofotbanen
            $customLine = "L8";
            if (!in_array($stopString, $L8x)) {
                $customLine = "L8x";
            }
        } else if ($trainNum >= 90 && $trainNum <= 99) {
            // Ofotbanen
            $customLine = "R80";
            if (!in_array($stopString, $R80x)) {
                $customLine = "R80x";
            }
        }
        return $customLine;
    }
}
