<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeederPart4 extends Seeder
{
    /**
     * Cuarta parte (final) de productos reales de B&R Tecnología
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'name')->toArray();

        $products = [
            // TINTAS GENERICAS
            ['cod' => '3117', 'name' => 'TINTA GENERICA PARA EPSON 673 T673 YELLOW ECOTANK 70ML L800 L810 L850 L1800 L805', 'category' => 'Tintas y Tóners'],
            ['cod' => '3118', 'name' => 'TINTA GENERICA PARA EPSON 673 T673 CYAN ECOTANK 70ML L800 L810 L850 L1800 L805', 'category' => 'Tintas y Tóners'],
            ['cod' => '3119', 'name' => 'TINTA GENERICA PARA EPSON 673 T673 MAGENTA ECOTANK 70ML L800 L810 L850 L1800 L805', 'category' => 'Tintas y Tóners'],
            ['cod' => '3120', 'name' => 'TINTA GENERICA PARA EPSON 673 T673 LIGHT CYAN ECOTANK 70ML L800 L810 L850 L1800 L805', 'category' => 'Tintas y Tóners'],
            ['cod' => '3121', 'name' => 'TINTA GENERICA PARA EPSON 673 T673 LIGHT MAGENTA ECOTANK 70ML L800 L810 L850 L1800 L805', 'category' => 'Tintas y Tóners'],
            ['cod' => '3116', 'name' => 'TINTA GENERICA PARA EPSON 673 T673 BLACK ECOTANK 70ML L800 L810 L850 L1800 L805', 'category' => 'Tintas y Tóners'],
            ['cod' => '6133', 'name' => 'TINTA GENERICA PARA EPSON 504/544 T504/T544 YELLOW ECOTANK 70ML L1110 L3110 L3150 L3160', 'category' => 'Tintas y Tóners'],
            ['cod' => '6134', 'name' => 'TINTA GENERICA PARA EPSON 504/544 T504/T544 CYAN ECOTANK 70ML L1110 L3110 L3150 L3160', 'category' => 'Tintas y Tóners'],
            ['cod' => '6135', 'name' => 'TINTA GENERICA PARA EPSON 504/544 T504/T544 MAGENTA ECOTANK 70ML L1110 L3110 L3150 L3160', 'category' => 'Tintas y Tóners'],
            ['cod' => '6132', 'name' => 'TINTA GENERICA PARA EPSON 504/544 T504/T544 BLACK ECOTANK 70ML L1110 L3110 L3150 L3160', 'category' => 'Tintas y Tóners'],

            // TINTAS BROTHER ORIGINALES
            ['cod' => '5046', 'name' => 'TINTA BROTHER BOTELLA ORIGINAL BTD60BK NEGRO 6500 PAG DCP-T310 DCP-T510W DCP-T710W MFC-T910DW DCP-T420W', 'category' => 'Tintas y Tóners'],
            ['cod' => '5049', 'name' => 'TINTA BROTHER BOTELLA ORIGINAL BT5001C CYAN 5000 PAG DCP-T310 DCP-T510W DCP-T710W MFC-T910DW DCP-T420W', 'category' => 'Tintas y Tóners'],
            ['cod' => '5050', 'name' => 'TINTA BROTHER BOTELLA ORIGINAL BT5001M MAGENTA 5000 PAG DCP-T310 DCP-T510W DCP-T710W MFC-T910DW DCP-T420W', 'category' => 'Tintas y Tóners'],
            ['cod' => '5047', 'name' => 'TINTA BROTHER BOTELLA ORIGINAL BT5001Y YELLOW 5000 PAG DCP-T310 DCP-T510W DCP-T710W MFC-T910DW DCP-T420W', 'category' => 'Tintas y Tóners'],

            // TINTAS CANON ORIGINALES
            ['cod' => '4039', 'name' => 'TINTA CANON GI-190 BOTELLA ORIGINAL NEGRO 135 ML G1100 G2100 G3100 G1110 G2110 G3110', 'category' => 'Tintas y Tóners'],
            ['cod' => '4041', 'name' => 'TINTA CANON GI-190 BOTELLA ORIGINAL CYAN 70 ML G1100 G2100 G3100 G1110 G2110 G3110', 'category' => 'Tintas y Tóners'],
            ['cod' => '4042', 'name' => 'TINTA CANON GI-190 BOTELLA ORIGINAL MAGENTA 70 ML G1100 G2100 G3100 G1110 G2110 G3110', 'category' => 'Tintas y Tóners'],
            ['cod' => '4040', 'name' => 'TINTA CANON GI-190 BOTELLA ORIGINAL YELLOW 70 ML G1100 G2100 G3100 G1110 G2110 G3110', 'category' => 'Tintas y Tóners'],

            // TINTAS HP ORIGINALES
            ['cod' => '5740', 'name' => 'TINTA HP BOTELLA GT52 ORIGINAL CYAN M0H54AL 70ML DeskJet GT5820/5810/315/415/115/InkTank', 'category' => 'Tintas y Tóners'],
            ['cod' => '5739', 'name' => 'TINTA HP BOTELLA GT52 ORIGINAL MAGENTA M0H55AL 70ML DeskJet GT5820/5810/315/415/115/InkTank', 'category' => 'Tintas y Tóners'],
            ['cod' => '5738', 'name' => 'TINTA HP BOTELLA GT52 ORIGINAL YELLOW M0H56AL 70ML DeskJet GT5820/5810/315/415/115/InkTank', 'category' => 'Tintas y Tóners'],
            ['cod' => '5719', 'name' => 'TINTA HP BOTELLA GT53 ORIGINAL BLACK 1VV22AL 90ML DeskJet GT5820/5810/315/415/115/InkTank', 'category' => 'Tintas y Tóners'],
            ['cod' => '8774', 'name' => 'TINTA HP BOTELLA GT53XL ORIGINAL BLACK 1VV21AL 135ML DeskJet GT5820/5810/315/415/115/InkTank', 'category' => 'Tintas y Tóners'],
            ['cod' => '5958', 'name' => 'TINTA HP CARTUCHO ORIGINAL 664XL BLACK F6V31AL DESKJET INK ADV. 1115 2135 2675 3635 3775 3785 3787 3835 4535 4675', 'category' => 'Tintas y Tóners'],
            ['cod' => '5959', 'name' => 'TINTA HP CARTUCHO ORIGINAL 664XL COLOR F6V30AL DESKJET INK ADV. 1115 2135 2675 3635 3775 3785 3787 3835 4535 4675', 'category' => 'Tintas y Tóners'],

            // TINTAS EPSON ORIGINALES
            ['cod' => '7691', 'name' => 'TINTA EPSON BOTELLA 544 T544120 NEGRO 65ML L1110 L3110 L3150 L3250 L3210 L3260 L1210 L1250 L5190 L5290', 'category' => 'Tintas y Tóners'],
            ['cod' => '7695', 'name' => 'TINTA EPSON BOTELLA 544 T544220 CYAN 65ML L1110 L3110 L3150 L3250 L3210 L3260 L1210 L1250 L5190 L5290', 'category' => 'Tintas y Tóners'],
            ['cod' => '7696', 'name' => 'TINTA EPSON BOTELLA 544 T544320 MAGENTA 65ML L1110 L3110 L3150 L3250 L3210 L3260 L1210 L1250 L5190 L5290', 'category' => 'Tintas y Tóners'],
            ['cod' => '7697', 'name' => 'TINTA EPSON BOTELLA 544 T544420 YELLOW 65ML L1110 L3110 L3150 L3250 L3210 L3260 L1210 L1250 L5190 L5290', 'category' => 'Tintas y Tóners'],
            ['cod' => '3107', 'name' => 'TINTA EPSON BOTELLA 664 T6641 BLACK ECOTANK 70ML L220 L380 L375 L395 L396 L4150 L575 L555', 'category' => 'Tintas y Tóners'],
            ['cod' => '3110', 'name' => 'TINTA EPSON BOTELLA 664 T6644 YELLOW ECOTANK 70ML L220 L380 L375 L395 L396 L4150 L575 L555', 'category' => 'Tintas y Tóners'],
            ['cod' => '3108', 'name' => 'TINTA EPSON BOTELLA 664 T6642 CYAN ECOTANK 70ML L220 L380 L375 L395 L396 L4150 L575 L555', 'category' => 'Tintas y Tóners'],
            ['cod' => '3109', 'name' => 'TINTA EPSON BOTELLA 664 T6643 MAGENTA ECOTANK 70ML L220 L380 L375 L395 L396 L4150 L575 L555', 'category' => 'Tintas y Tóners'],
            ['cod' => '6047', 'name' => 'TINTA EPSON BOTELLA 673 T673120 BLACK ECOTANK 70ML L800 L810 L1800 L805 L850', 'category' => 'Tintas y Tóners'],
            ['cod' => '6046', 'name' => 'TINTA EPSON BOTELLA 673 T673220 CYAN ECOTANK 70ML L800 L810 L1800 L805 L850', 'category' => 'Tintas y Tóners'],
            ['cod' => '6045', 'name' => 'TINTA EPSON BOTELLA 673 T673320 MAGENTA ECOTANK 70ML L800 L810 L1800 L805 L850', 'category' => 'Tintas y Tóners'],
            ['cod' => '6044', 'name' => 'TINTA EPSON BOTELLA 673 T673420 YELLOW ECOTANK 70ML L800 L810 L1800 L805 L850', 'category' => 'Tintas y Tóners'],
            ['cod' => '6043', 'name' => 'TINTA EPSON BOTELLA 673 T673520 LIGHT CYAN ECOTANK 70ML L800 L810 L1800 L805 L850', 'category' => 'Tintas y Tóners'],
            ['cod' => '6042', 'name' => 'TINTA EPSON BOTELLA 673 T673620 LIGHT MAGENTA ECOTANK 70ML L800 L810 L1800 L805 L850', 'category' => 'Tintas y Tóners'],

            // TELEVISORES
            ['cod' => '9232', 'name' => 'TELEVISOR LG SMART 43 43UT8050PSB UHD 4K MAGIC REMOTE AI ThinQ', 'category' => 'Televisores'],
            ['cod' => '9234', 'name' => 'TELEVISOR LG SMART 50 50UT8050PSB UHD 4K MAGIC REMOTE AI ThinQ', 'category' => 'Televisores'],
            ['cod' => '9269', 'name' => 'TELEVISOR LG SMART 55 55UT8050PSB UHD 4K MAGIC REMOTE AI ThinQ', 'category' => 'Televisores'],
            ['cod' => '9940', 'name' => 'TELEVISOR LG SMART 65 65UT8050PSB UHD 4K MAGIC REMOTE AI ThinQ', 'category' => 'Televisores'],
            ['cod' => '10082', 'name' => 'TELEVISOR LG SMART 75 75UT8050PSB UHD 4K MAGIC REMOTE AI ThinQ', 'category' => 'Televisores'],
            ['cod' => '10009', 'name' => 'TELEVISOR RCA SMART TV GOOGLE TV 32 RC32GG24S FHD HDMI USB RJ45 BLACK', 'category' => 'Televisores'],
            ['cod' => '10014', 'name' => 'TELEVISOR RCA SMART TV GOOGLE TV 43 RC43RG24S FHD HDMI USB RJ45 BLACK', 'category' => 'Televisores'],
            ['cod' => '10013', 'name' => 'TELEVISOR RCA SMART TV GOOGLE TV 65 RC65RG24S UHD HDMI USB RJ45 BLACK', 'category' => 'Televisores'],
            ['cod' => '9884', 'name' => 'TELEVISOR RCA SMART TV GOOGLE TV 75 RC75RG24S UHD HDMI USB RJ45 BLACK', 'category' => 'Televisores'],
            ['cod' => '10106', 'name' => 'TELEVISOR TCL 32S5400AF 32 SMART TV FHD ANDROID TV ASISTENTE DE GOOGLE LED', 'category' => 'Televisores'],
            ['cod' => '8952', 'name' => 'TELEVISOR TCL 75P635 75 SMART TV UHD 4K GOOGLE TV ASISTENTE DE GOOGLE LED', 'category' => 'Televisores'],
            ['cod' => '10076', 'name' => 'TELEVISOR TCL 43P79B 43 SMART TV UHD 4K GOOGLE TV ASISTENTE DE GOOGLE LED', 'category' => 'Televisores'],
            ['cod' => '10077', 'name' => 'TELEVISOR TCL 50P79B 50 SMART TV UHD 4K GOOGLE TV ASISTENTE DE GOOGLE LED', 'category' => 'Televisores'],
            ['cod' => '10078', 'name' => 'TELEVISOR TCL 55P79B 55 SMART TV UHD 4K GOOGLE TV ASISTENTE DE GOOGLE LED', 'category' => 'Televisores'],
            ['cod' => '10079', 'name' => 'TELEVISOR TCL 65P79B 65 SMART TV UHD 4K GOOGLE TV ASISTENTE DE GOOGLE LED', 'category' => 'Televisores'],
            ['cod' => '10080', 'name' => 'TELEVISOR TCL 75P79B 75 SMART TV UHD 4K GOOGLE TV ASISTENTE DE GOOGLE LED', 'category' => 'Televisores'],

            // TV BOX
            ['cod' => '696', 'name' => 'TV BOX XIAOMI MI STICK 4K 2+8GB HDMI USB WIFI ANDROID', 'category' => 'TV Box'],
            ['cod' => '8602', 'name' => 'TV BOX 4K TANIX TX6S 4+32GB WIFI 6 HDMI USB BLUETOOTH ANDROID 10 BLACK', 'category' => 'TV Box'],
            ['cod' => '7973', 'name' => 'TV BOX XIAOMI TV BOX S 2+8GB HDMI USB WIFI ANDROID', 'category' => 'TV Box'],
            ['cod' => '8486', 'name' => 'AMAZON FIRE TV STICK 4K MAX B09BN45CGN REMOTE WIFI 6E ALEXA', 'category' => 'TV Box'],
            ['cod' => '10049', 'name' => 'TV BOX XIAOMI TV BOX S 4K (2ND GEN) 2+8GB HDMI USB WIFI GOOGLE TV BLACK', 'category' => 'TV Box'],

            // GENERADORES
            ['cod' => '10319', 'name' => 'GENERADOR OVI OVI5500E MOTOR 6.5HP 5500W AVR ARRANQUE ELECTRICO/MANUAL NEGRO', 'category' => 'Generadores'],
            ['cod' => '10320', 'name' => 'GENERADOR OVI OVI7500E MOTOR 7.5HP 7500W AVR ARRANQUE ELECTRICO/MANUAL NEGRO', 'category' => 'Generadores'],
            ['cod' => '10318', 'name' => 'GENERADOR OVI OVI3500E MOTOR 3.5HP 3500W AVR ARRANQUE ELECTRICO/MANUAL NEGRO', 'category' => 'Generadores'],
            ['cod' => '10321', 'name' => 'GENERADOR OVI OVI10000TS MOTOR 15HP DIESEL 10000W AVR ARRANQUE ELECTRICO/MANUAL NEGRO', 'category' => 'Generadores'],
            ['cod' => '7098', 'name' => 'GENERADOR PULSAR 2200W / 1750W INVERTER PULLARA PG2200ISA SIN GASOLINA', 'category' => 'Generadores'],
            ['cod' => '8825', 'name' => 'GENERADOR INFINYTEK INFYTG1000 2 TIEMPOS 700W 3A ARRANQUE MANUAL 4L GASOLINA', 'category' => 'Generadores'],
            ['cod' => '8827', 'name' => 'GENERADOR INFINYTEK INFYTG2500 4 TIEMPOS 2000W 7.2A ARRANQUE MANUAL 15L GASOLINA', 'category' => 'Generadores'],
            ['cod' => '8828', 'name' => 'GENERADOR INFINYTEK INFYTG3500 4 TIEMPOS 3000W 10.8A ARRANQUE MANUAL 15L GASOLINA', 'category' => 'Generadores'],
            ['cod' => '8830', 'name' => 'GENERADOR INFINYTEK INFYTG8000 4 TIEMPOS 6500W 23.4A ARRANQUE ELECTRICO/MANUAL 25L GASOLINA', 'category' => 'Generadores'],
            ['cod' => '6691', 'name' => 'GENERADOR FIRMAN FPG3800E2 2.8KW 110/220V / 3800W MAX 120V/240V', 'category' => 'Generadores'],
            ['cod' => '6699', 'name' => 'GENERADOR FIRMAN FPG10000SE-CHINA 8KW 110/220V', 'category' => 'Generadores'],
            ['cod' => '6698', 'name' => 'GENERADOR FIRMAN FPG7800E2 6KW 110/220V', 'category' => 'Generadores'],
            ['cod' => '6697', 'name' => 'GENERADOR FIRMAN FPG5800E2 4.5KW 110/220V', 'category' => 'Generadores'],
            ['cod' => '6696', 'name' => 'GENERADOR ALL POWER APG3800 3.3KW 3300W 120/240V', 'category' => 'Generadores'],
            ['cod' => '10300', 'name' => 'GENERADOR FIRMAN FPG4050 3.3KW 3050W RUNNING 3550 STARTING 110/220V', 'category' => 'Generadores'],

            // POWER STATION
            ['cod' => '9702', 'name' => 'POWER STATION BLUETTI AC180 1800W 1152WH LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '9703', 'name' => 'POWER STATION BLUETTI AC70 1000W 768WH LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10302', 'name' => 'POWER STATION ECOFLOW DELTA 2 MAX 2048Wh AC: 2,400W/X-BOOST: 3,400W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10303', 'name' => 'POWER STATION ECOFLOW DELTA 2 1024Wh AC: 1,800W/X-BOOST: 2,700W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10304', 'name' => 'POWER STATION ECOFLOW DELTA PRO ULTRA 6144Wh AC: 7,200W X-STREAM: 7,200W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10305', 'name' => 'POWER STATION ECOFLOW RIVER 2 256Wh AC: 300W/X-BOOST: 600W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10306', 'name' => 'POWER STATION ECOFLOW RIVER 2 PRO 768Wh AC: 800W/X-BOOST: 1,600W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10307', 'name' => 'PANEL SOLAR ECOFLOW 400W PLEGABLE PORTATIL', 'category' => 'Power Station'],
            ['cod' => '8837', 'name' => 'POWER STATION FORZA FORTIS 1200 GENERADOR PORTATIL 1200W 999WH BATT.LITIO', 'category' => 'Power Station'],
            ['cod' => '8993', 'name' => 'POWER STATION FORZA FORTIS 600 GENERADOR PORTATIL 600W 570WH BATT.LITIO', 'category' => 'Power Station'],
            ['cod' => '10308', 'name' => 'POWER STATION JACKERY EXPLORER 1000 PLUS 1264Wh AC: 2,000W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10309', 'name' => 'POWER STATION JACKERY EXPLORER 2000 PLUS 2042Wh AC: 3,000W LiFePO4 PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10310', 'name' => 'PANEL SOLAR JACKERY SOLARSAGA 200W PLEGABLE PORTATIL', 'category' => 'Power Station'],
            ['cod' => '10311', 'name' => 'PANEL SOLAR JACKERY SOLARSAGA 100W PLEGABLE PORTATIL', 'category' => 'Power Station'],

            // UPS
            ['cod' => '2785', 'name' => 'UPS POWEST MICRONET 750VA 380W/6 TOMAS/PROTECCION PARA DATOS Y FAX/LED 120V', 'category' => 'UPS'],
            ['cod' => '2790', 'name' => 'UPS POWEST MICRONET 1000VA 500W/6 TOMAS/PROTECCION PARA DATOS Y FAX/LED 120V', 'category' => 'UPS'],
            ['cod' => '2791', 'name' => 'UPS POWEST MICRONET 1500VA 900W/8 TOMAS/PROTECCION PARA DATOS Y FAX/LED 120V', 'category' => 'UPS'],
            ['cod' => '2789', 'name' => 'UPS POWEST MICRONET 2000VA 1200W/8 TOMAS/PROTECCION PARA DATOS Y FAX/LED 120V', 'category' => 'UPS'],
            ['cod' => '2787', 'name' => 'UPS POWEST MICRONET 2250VA 1350W/8 TOMAS/PROTECCION PARA DATOS Y FAX/LED 120V', 'category' => 'UPS'],
            ['cod' => '7086', 'name' => 'UPS POWEST MICRONET 3000VA 1800W/8 TOMAS/PROTECCION PARA DATOS Y FAX/LED 120V', 'category' => 'UPS'],
            ['cod' => '8855', 'name' => 'UPS POWEST CORE 3KVA 2700W ON-LINE RACK/TOWER 2U PANTALLA LCD 5 AÑOS BATERIA', 'category' => 'UPS'],
            ['cod' => '8856', 'name' => 'UPS POWEST CORE 6KVA 5400W ON-LINE RACK/TOWER 2U PANTALLA LCD 5 AÑOS BATERIA', 'category' => 'UPS'],
            ['cod' => '8857', 'name' => 'UPS POWEST CORE 10KVA 9000W ON-LINE RACK/TOWER 2U PANTALLA LCD 5 AÑOS BATERIA', 'category' => 'UPS'],
            ['cod' => '3022', 'name' => 'UPS APC BACK-UPS BX625CI-MS 625VA 375W/4 TOMAS 230V', 'category' => 'UPS'],
            ['cod' => '3021', 'name' => 'UPS APC BACK-UPS PRO BR1500M2-LM 1500VA 900W/10 TOMAS LCD', 'category' => 'UPS'],
            ['cod' => '10313', 'name' => 'UPS SAT PRO 521L 1200VA 720W/6 TOMAS/LED 120V', 'category' => 'UPS'],
            ['cod' => '10314', 'name' => 'UPS SAT PRO 522L 2000VA 1200W/6 TOMAS/LED 120V', 'category' => 'UPS'],
            ['cod' => '4608', 'name' => 'UPS TRIPPLITE SMART750USB 750VA 450W/6 TOMAS USB', 'category' => 'UPS'],
            ['cod' => '4609', 'name' => 'UPS TRIPPLITE SMART1000LCD 1000VA 500W/8 TOMAS LCD', 'category' => 'UPS'],
            ['cod' => '4610', 'name' => 'UPS TRIPPLITE SMART1500LCDT 1500VA 900W/10 TOMAS LCD', 'category' => 'UPS'],
            ['cod' => '9553', 'name' => 'UPS HIKVISION DS-UPS1000-FNB 1000VA 600W/3 TOMAS LED 120V', 'category' => 'UPS'],
            ['cod' => '9554', 'name' => 'UPS HIKVISION DS-UPS1200-FNB 1200VA 720W/4 TOMAS LED 120V', 'category' => 'UPS'],

            // POWER BANK
            ['cod' => '4782', 'name' => 'POWER BANK HYPERGEAR 10000MAH 2USB BLACK', 'category' => 'Power Bank'],
            ['cod' => '4783', 'name' => 'POWER BANK HYPERGEAR 20000MAH 2USB BLACK', 'category' => 'Power Bank'],
            ['cod' => '9707', 'name' => 'POWER BANK ANKER 313 A1109 POWERCORE 10000MAH 12W USBA BLACK', 'category' => 'Power Bank'],
            ['cod' => '9706', 'name' => 'POWER BANK ANKER 335 A1353 POWERBANK 20000MAH 22.5W USBA+USBC BLACK', 'category' => 'Power Bank'],
            ['cod' => '9709', 'name' => 'POWER BANK ANKER PRIME A1335011 20000MAH 200W USBC X2 BLACK', 'category' => 'Power Bank'],
            ['cod' => '10316', 'name' => 'POWER BANK AUKEY PB-N83 BASIX SLIM 10000MAH DUAL USB BLACK', 'category' => 'Power Bank'],
            ['cod' => '10317', 'name' => 'POWER BANK ADATA AP10000QCD-DGT 10000MAH 22.5W USB-C+USB-A BLACK', 'category' => 'Power Bank'],
            ['cod' => '4784', 'name' => 'POWER BANK BELKIN BPB004BTWH 10000MAH 18W USB-C BLACK', 'category' => 'Power Bank'],
            ['cod' => '7336', 'name' => 'CARGADOR DE BATERIA RECARGABLE UNIVERSAL PAPA / PULPO', 'category' => 'Power Bank'],

            // VENTILADORES PC
            ['cod' => '3695', 'name' => 'VENTILADOR PARA LAPTOP COOLER MASTER NOTEPAL U3 PLUS 3 FANS', 'category' => 'Ventiladores PC'],
            ['cod' => '4524', 'name' => 'VENTILADOR PARA LAPTOP COOLER MASTER NOTEPAL X-SLIM II 1 FAN', 'category' => 'Ventiladores PC'],
            ['cod' => '7000', 'name' => 'VENTILADOR PARA LAPTOP KLIPX KNBC-201N 2 FANS 17', 'category' => 'Ventiladores PC'],
            ['cod' => '8476', 'name' => 'VENTILADOR DE CPU COOLER MASTER HYPER 212 EVO V2 LED WHITE', 'category' => 'Ventiladores PC'],
            ['cod' => '3694', 'name' => 'VENTILADOR PARA PC COOLER MASTER SICKLEFLOW 120 RGB 3 IN 1', 'category' => 'Ventiladores PC'],
            ['cod' => '3693', 'name' => 'VENTILADOR PARA PC COOLER MASTER MASTERLIQUID ML240L V2 RGB AIO CPU', 'category' => 'Ventiladores PC'],
            ['cod' => '4523', 'name' => 'VENTILADOR PARA PC COOLER MASTER MASTERAIR MA410M RGB', 'category' => 'Ventiladores PC'],
            ['cod' => '5788', 'name' => 'VENTILADOR PARA PROCESADOR INTEL-AMD COOLER MASTER HYPER 212 BLACK EDITION', 'category' => 'Ventiladores PC'],
        ];

        $created = 0;
        $skipped = 0;
        foreach ($products as $product) {
            $categoryId = $categoryIds[$product['category']] ?? null;
            if (!$categoryId) {
                $this->command->warn("Categoría no encontrada: {$product['category']}");
                continue;
            }

            // Verificar si ya existe el producto
            if (Product::where('sku_code', $product['cod'])->exists()) {
                $skipped++;
                continue;
            }

            Product::create([
                'sku_code' => $product['cod'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name'] . '-' . $product['cod']),
                'description' => $product['name'],
                'technical_specs' => json_encode(['COD' => $product['cod']]),
                'category_id' => $categoryId,
                'stock_available' => rand(5, 50),
                'price_base' => rand(10, 800) + (rand(0, 99) / 100),
                'is_active' => true,
                'is_featured' => rand(0, 10) > 8,
            ]);
            $created++;
        }
        
        if ($skipped > 0) {
            $this->command->info("⏭️ Productos saltados (ya existían): {$skipped}");
        }

        $this->command->info("✅ Cuarta parte (FINAL) - Productos creados: {$created}");
        
        // Resumen final
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $this->command->info("========================================");
        $this->command->info("📊 RESUMEN TOTAL DEL INVENTARIO B&R:");
        $this->command->info("   Total de Productos: {$totalProducts}");
        $this->command->info("   Total de Categorías: {$totalCategories}");
        $this->command->info("========================================");
    }
}
