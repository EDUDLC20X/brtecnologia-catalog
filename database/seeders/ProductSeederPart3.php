<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeederPart3 extends Seeder
{
    /**
     * Tercera parte de productos reales de B&R Tecnología
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id', 'name')->toArray();

        $products = [
            // MOUSE
            ['cod' => '6028', 'name' => 'MOUSE INFINYTEK M104 BLACK CAJA NEGRA & TOMATE - M104', 'category' => 'Mouse'],
            ['cod' => '8621', 'name' => 'MOUSE INFINYTEK VERTICAL ERGONOMICO NEGRO PUERTO USB CABLEADO JSY-02', 'category' => 'Mouse'],
            ['cod' => '8641', 'name' => 'MOUSE INFINYTEK INALAMBRICO M619 2,4GHZ WIRELESS ERGONOMICO VERTICAL - RECARGABLE', 'category' => 'Mouse'],
            ['cod' => '10275', 'name' => 'MOUSE XTRATECH XTR-255BLK-PRO ALAMBRICO USB NEGRO', 'category' => 'Mouse'],
            ['cod' => '4779', 'name' => 'MOUSE SPEEDMIND OPTICO SMMOU09 NEGRO USB', 'category' => 'Mouse'],
            ['cod' => '9365', 'name' => 'MOUSE MARVO MS003WH OPTICO USB BLANCO', 'category' => 'Mouse'],
            ['cod' => '10261', 'name' => 'MOUSE MARVO GM217 7 COLORES DIFERENTES USB', 'category' => 'Mouse'],
            ['cod' => '7493', 'name' => 'MOUSE LENOVO ESSENTIAL USB 4Y50R20863', 'category' => 'Mouse'],
            ['cod' => '4224', 'name' => 'MOUSE LOGITECH M110 SILENT USB BLACK', 'category' => 'Mouse'],
            ['cod' => '479', 'name' => 'MOUSE ANERA ERGONOMICO AE-MCV06 CON CABLE USB', 'category' => 'Mouse'],
            ['cod' => '10257', 'name' => 'MOUSE MARVO M360 TEPO-70 GAMER RETROILUMINACION RGB CON 7 MODOS PROGRAMABLE CABLE 8D USB', 'category' => 'Mouse'],
            ['cod' => '10258', 'name' => 'MOUSE MARVO M411 DUQUE 60 GAMER RETROILUMINACION RGB 6 NIVELES DPI DISEÑO ERGONOMICO USB', 'category' => 'Mouse'],
            ['cod' => '10260', 'name' => 'MOUSE MARVO M803W DUKE 20 GAMER CON CABLE Y MODELO DUAL 2.4G RECARGABLE', 'category' => 'Mouse'],
            ['cod' => '10259', 'name' => 'MOUSE MARVO M655 CAPO 35 GAMING SENSOR OPTICO 6400 DPI RGB DISEÑO ERGNONOMICO USB', 'category' => 'Mouse'],
            ['cod' => '5496', 'name' => 'MOUSE DELUX DLM618PU (A825) ERGONOMICO 12800DPI USB BLACK', 'category' => 'Mouse'],
            ['cod' => '5614', 'name' => 'MOUSE DELUX M618PD ERGONOMICO RECARGABLE INHALAMBRICO NEGRO', 'category' => 'Mouse'],
            ['cod' => '204', 'name' => 'MOUSE GENIUS MICRO TRAVELER USB RET. NEGRO', 'category' => 'Mouse'],
            ['cod' => '203', 'name' => 'MOUSE GENIUS MICRO TRAVELER RET. USB WHITE', 'category' => 'Mouse'],
            ['cod' => '205', 'name' => 'MOUSE GENIUS MICRO TRAVELER USB RET. RUBY', 'category' => 'Mouse'],
            ['cod' => '206', 'name' => 'MOUSE GENIUS MICRO TRAVELER USB RET. SILVER', 'category' => 'Mouse'],
            ['cod' => '538', 'name' => 'MOUSE GENIUS ECO-8100 WIRELESS BLUEEYE RECARGABLE BLACK', 'category' => 'Mouse'],
            ['cod' => '2754', 'name' => 'MOUSE GENIUS ECO-8100 WIRELESS BLUEEYE RECARGABLE BLUE', 'category' => 'Mouse'],
            ['cod' => '7834', 'name' => 'MOUSE GENIUS ERGO 8250S INALAMBRICO SILENCIOSO 6 BOTONES + PILA AA SILVER ERGONOMICO VERTICAL', 'category' => 'Mouse'],
            ['cod' => '7835', 'name' => 'MOUSE GENIUS ERGO 8250S INALAMBRICO SILENCIOSO 6 BOTONES + PILA AA CHAMPAGNE ERGONOMICO VERTICAL', 'category' => 'Mouse'],
            ['cod' => '8540', 'name' => 'MOUSE GENIUS NETSCROLL DX-101 OPTICO USB BLACK', 'category' => 'Mouse'],
            ['cod' => '8542', 'name' => 'MOUSE GENIUS ERGO 9000S INALAMBRICO RECARGABLE LIGTH BLUE ERGONOMICO VERTICAL RGB', 'category' => 'Mouse'],
            ['cod' => '9950', 'name' => 'MOUSE GENIUS ERGO 9000S INALAMBRICO RECARGABLE WOOD ERGONOMICO VERTICAL RGB', 'category' => 'Mouse'],
            ['cod' => '9951', 'name' => 'MOUSE GENIUS ECO-8015 WIRELESS BLUEEYE RECARGABLE IRON GRAY', 'category' => 'Mouse'],
            ['cod' => '9952', 'name' => 'MOUSE GENIUS ECO-8015 WIRELESS BLUEEYE RECARGABLE SILVER', 'category' => 'Mouse'],
            ['cod' => '9953', 'name' => 'MOUSE GENIUS ECO-8100 WIRELESS BLUEEYE RECARGABLE RED', 'category' => 'Mouse'],
            ['cod' => '9954', 'name' => 'MOUSE GENIUS NX-7005 INHALMBRICO BLACK', 'category' => 'Mouse'],
            ['cod' => '8445', 'name' => 'MOUSE GENIUS SCORPION M715 RGB USB LED 6 BOTONES DPI 800 A 7200', 'category' => 'Mouse'],
            ['cod' => '5586', 'name' => 'MOUSE LENOVO NEGRO ALAMBRICO USB NEGRO', 'category' => 'Mouse'],
            ['cod' => '8543', 'name' => 'MOUSE GENIUS ERGO 9000S INALAMBRICO RECARGABLE SILVER ERGONOMICO VERTICAL RGB', 'category' => 'Mouse'],
            ['cod' => '8577', 'name' => 'MOUSE GENIUS ERGO 8300S INALAMBRICO SILENCIOSO 6 BOTONES + PILA AA BLACK ERGONOMICO VERTICAL', 'category' => 'Mouse'],

            // PAD MOUSE
            ['cod' => '231', 'name' => 'PAD MOUSE VIEWSTAR CON DISEÑO', 'category' => 'Pad Mouse'],
            ['cod' => '393', 'name' => 'PAD MOUSE CON GEL KLIPX KMP-100B - ALFOMBRILLA DE RATON CON APOYAMUÑECAS - NEGRO', 'category' => 'Pad Mouse'],
            ['cod' => '9610', 'name' => 'PAD MOUSE INFINYTEK CON APOYADERA DE ALTA CALIDAD (247x215x20mm)', 'category' => 'Pad Mouse'],

            // PRODUCTOS ALEXA / AMAZON
            ['cod' => '7327', 'name' => 'PARLANTE AMAZON ECHO POP (1ST GEN) SMART SPEAKER WITH ALEXA CHARCOAL', 'category' => 'Productos Amazon'],
            ['cod' => '10197', 'name' => 'PARLANTE AMAZON ECHO SPOT B0BFC7WQ6R NEGRO RELOJ ALARMA ALEXA PANTALLA 2.8', 'category' => 'Productos Amazon'],
            ['cod' => '8996', 'name' => 'PANTALLA AMAZON ECHO SHOW 5 (3RA GENERACION 2023) B09B2SRGXH AZUL PANTA. TACTIL 5.5 2MP CAMARA', 'category' => 'Productos Amazon'],

            // PARLANTES
            ['cod' => '9735', 'name' => 'PARLANTE GENIUS SP-U125 NEGRO 3W USB E. AUDIO 3.5MM CONECTOR AURICULARES CONTROL VOLUMEN', 'category' => 'Parlantes'],
            ['cod' => '2756', 'name' => 'PARLANTE GENIUS SOUNDBAR 100 USB RMS 6W AUXILIAR IN 3.55MM 80DB CONTROL VOLUMEN NEGRO', 'category' => 'Parlantes'],
            ['cod' => '3167', 'name' => 'PARLANTE GENIUS SP-HF180 USB WOOD', 'category' => 'Parlantes'],
            ['cod' => '3304', 'name' => 'PARLANTE GENIUS SP-HF180 USB NEGRO', 'category' => 'Parlantes'],
            ['cod' => '8934', 'name' => 'PARLANTE KLIPX KBS250 ZOUNDFIRE 12W USB-C EFECTOS DE LUZ 5 TIPOS INALAMBRICO 4HRS', 'category' => 'Parlantes'],
            ['cod' => '1492', 'name' => 'PARLANTE KLIPX KBS350BK ZOUNDFIRE PRO LUCES LED 16W 11HRS INALAMBRICO BL TWS BLACK', 'category' => 'Parlantes'],
            ['cod' => '8979', 'name' => 'BARRA DE SONIDO KLIP XTREME HARMONIUM KSB-001 2.0 SOUNDBAR WITH BLUETOOTH TECHNOLOGY USB HDMI AUXILIAR 3.5MM', 'category' => 'Parlantes'],
            ['cod' => '8742', 'name' => 'PARLANTE KLIPX ORYX KBS600 WIRELESS 31W IPX7 TWS 13HRS NEGRO', 'category' => 'Parlantes'],
            ['cod' => '8925', 'name' => 'PARLANTE KLIP XTREME BOOMFIRE KLS-100 4 2X LUCES LED DINAMICAS Y MULTICOLORES 1200W 80W USB MICRO SD RAFIO FM INALAMBRICO', 'category' => 'Parlantes'],
            ['cod' => '8924', 'name' => 'PARLANTE KLIP XTREME MAGBLASTER KLS-601 8 2000 WATTS 2X LUCES LED', 'category' => 'Parlantes'],
            ['cod' => '6945', 'name' => 'PARLANTE KLIPX MAG BLASTER PRO KLS890 PORTABLE PARTY 2X12 WIRELESS MICROFONO+ CONTROL NEGRO', 'category' => 'Parlantes'],
            ['cod' => '8926', 'name' => 'PARLANTE KLIP XTREME BOOMFIRE PRO KLS-662 8 + iI 2X LUCES LED FLAMEANTES Y MULTICOLORES', 'category' => 'Parlantes'],
            ['cod' => '4190', 'name' => 'PARLANTE AMPLIFICADO RCA 50000W AL-1504 15 TWS USB TF CARD BLUETOOTH RECARGABLE LUCES LED RGB MICROFONO WIRELEES NEGRO', 'category' => 'Parlantes'],
            ['cod' => '8607', 'name' => 'PARLANTE AMPLIFICADO RCA 50000W AL-1504 15 TWS USB TF CARD BLUETOOTH RADIO FM RECARGABLE LUCES LED RGB MICROFONO WIRELEES NEGRO', 'category' => 'Parlantes'],
            ['cod' => '9944', 'name' => 'PARLANTE AMPLIFICADO RCA 50000W AL-1504 15 TWS USB TF CARD BLUETOOTH RADIO FM RECARGABLE LUCES LED RGB MICROFONO WIRELEES AZUL', 'category' => 'Parlantes'],
            ['cod' => '9945', 'name' => 'PARLANTE AMPLIFICADO RCA 50000W AL-1504 15 TWS USB TF CARD BLUETOOTH RADIO FM RECARGABLE LUCES LED RGB MICROFONO WIRELEES ROJA', 'category' => 'Parlantes'],
            ['cod' => '8851', 'name' => 'PEDESTAL PARA CAJA AMPLIFICADA SPS-502M', 'category' => 'Parlantes'],

            // PANTALLA PARA PORTATIL
            ['cod' => '619', 'name' => 'PANTALLA PORTATIL LED SLIM 11.6 40 P', 'category' => 'Pantallas para Portátil'],
            ['cod' => '3699', 'name' => 'PANTALLA PORTATIL LED SLIM DELL 15.6 TOUCH 1080P NO BRACKETS', 'category' => 'Pantallas para Portátil'],

            // PAPEL BOND / PAPEL FOTOGRAFICO / PAPEL TERMICO
            ['cod' => '5727', 'name' => 'ROLLOS DE PAPEL TERMICO 79x70 METROS', 'category' => 'Papel y Suministros'],
            ['cod' => '9879', 'name' => 'ROLLO DE PAPEL SAT TERMICO 80MMX60MTS 48GRM', 'category' => 'Papel y Suministros'],
            ['cod' => '10202', 'name' => 'RESMA PAPEL BOND EXCELLENT 500H 75G', 'category' => 'Papel y Suministros'],
            ['cod' => '9914', 'name' => 'RESMA PAPEL BOND EMERALD 500H 75G', 'category' => 'Papel y Suministros'],
            ['cod' => '1219', 'name' => 'RESMA PAPEL BOND REPORT 500H 75G', 'category' => 'Papel y Suministros'],

            // PROCESADORES
            ['cod' => '7899', 'name' => 'PROCESADOR AMD AM4 RYZEN 7 5700X 3.8GHZ 8 CORE 16 HILOS 16MB CACHE 65W TSMC SIN VENTILADOR', 'category' => 'Procesadores'],
            ['cod' => '5229', 'name' => 'PROCESADOR INTEL CORE I3 12100 (12VA) DE 3.3GHZ HASTA 4.30GHZ 12MB CACHE CON VIDEO', 'category' => 'Procesadores'],
            ['cod' => '5179', 'name' => 'PROCESADOR INTEL CORE I5 12400 (12VA) 2.50-4.40GHZ 18MB LGA1700 CON VIDEO', 'category' => 'Procesadores'],
            ['cod' => '8241', 'name' => 'PROCESADOR INTEL CORE i5 13400F (13VA) SIN VIDEO', 'category' => 'Procesadores'],
            ['cod' => '8242', 'name' => 'PROCESADOR INTEL CORE i5 13400 (13VA) CON VIDEO', 'category' => 'Procesadores'],
            ['cod' => '5178', 'name' => 'PROCESADOR INTEL CORE I7 12700 (12VA) 2.10-4.90GHZ 25MB LGA1700 CON VIDEO', 'category' => 'Procesadores'],
            ['cod' => '8245', 'name' => 'PROCESADOR INTEL CORE i7 14700 (14VA) CON VIDEO', 'category' => 'Procesadores'],

            // PROYECTORES
            ['cod' => '8455', 'name' => 'PROYECTOR WANBO T2 MAX LCD 450 ANSI 1920x1080 AUTO FOCUS RAM 1GB ROM 16GB ANDROID 9.0 HDMI USB DUAL BAND WIFI 6 WHITE', 'category' => 'Proyectores'],
            ['cod' => '8457', 'name' => 'PROYECTOR WANBO MOZART 1 LCD 900 ANSI 1920x1080 AUTO FOCUS RAM 2GB ROM 32GB HDMI USB DUAL BAND WIFI ALTAVOZ 8Wx2 WHITE', 'category' => 'Proyectores'],
            ['cod' => '4517', 'name' => 'PROYECTOR EPSON POWERLITE E20 3400 LUMENES XGA 3LCD 4:3 PANTALLA 30 HASTA 350 (GARANTIA 1 AÑO en LAMPARA 90 DIAS)', 'category' => 'Proyectores'],

            // PROTECTORES / REGULADORES
            ['cod' => '2964', 'name' => 'REGULADOR DE VOLTAJE POWEST PROPC 1000VA 8TOMAS', 'category' => 'Protectores y Reguladores'],
            ['cod' => '7902', 'name' => 'REGULADOR DE VOLTAJE POWEST PROPC 2200VA 8TOMAS', 'category' => 'Protectores y Reguladores'],
            ['cod' => '441', 'name' => 'PROTECTOR DE VOLTAJE INS 2000VA AVR2000WT NEGRO 8TOMAS (PC+MONITOR-IMPRESORAS-TV ROUTER-LAPTOP)', 'category' => 'Protectores y Reguladores'],

            // SMART WATCH
            ['cod' => '9258', 'name' => 'SMART WATCH RCA TRONIX BLACK 1.38 BLUETOOTH IP68 100 SPORT MODES LONG BATERY COMPATIBLE CON ALEXA', 'category' => 'Smart Watch'],
            ['cod' => '9257', 'name' => 'SMART WATCH RCA TRONIX NIGHT BLUE 1.38 BLUETOOTH IP68 100 SPORT MODES LONG BATERY COMPATIBLE CON ALEXA', 'category' => 'Smart Watch'],
            ['cod' => '10103', 'name' => 'SMART WATCH KIESLECT CALLING WATCH KS MINI BLUE', 'category' => 'Smart Watch'],
            ['cod' => '10094', 'name' => 'SMART WATCH INFINIX BLACK (SQUARE) - MODEL XW3P', 'category' => 'Smart Watch'],
            ['cod' => '10104', 'name' => 'SMART WATCH KIESLECT CALLING WATCH KS2 SPACE GRAY', 'category' => 'Smart Watch'],
            ['cod' => '10095', 'name' => 'SMART WATCH INFINIX XWATCH GT GREY - MODEL XW3GT', 'category' => 'Smart Watch'],

            // ESCANER
            ['cod' => '1598', 'name' => 'ESCANER - SCANNER EPSON WORKFORCE DS-530II 35PPM/ 70IPM/UBS 2.0', 'category' => 'Escáneres'],

            // ROUTER / ACCESS POINT
            ['cod' => '2021', 'name' => 'ROUTER TP-LINK ARCHER C50 WIRELESS AC1200 WIFI DE DOBLE BANDA 4 ANTENAS', 'category' => 'Routers y Access Point'],
            ['cod' => '10200', 'name' => 'ROUTER MERCUSYS AC1300 DOBLE BANDA HIGH SPEED WIFI 4ANTENAS NEGRO', 'category' => 'Routers y Access Point'],
            ['cod' => '7465', 'name' => 'ROUTER LINKSYS EA6350-4B DUAL BAND AC1200 WIFI 5', 'category' => 'Routers y Access Point'],
            ['cod' => '8354', 'name' => 'ROUTER MERCUSYS MR50G WIRELLES AC1900 6 ANTENAS', 'category' => 'Routers y Access Point'],
            ['cod' => '4925', 'name' => 'ROUTER TP-LINK ARCHER C64 WIRELESS AC1200 DUAL BAND MU-MINO / 4 ANTENAS', 'category' => 'Routers y Access Point'],
            ['cod' => '2972', 'name' => 'ROUTER TP-LINK ARCHER C80 WIRELESS AC1900 DUAL BAND MU-MINO / 4 ANTENAS', 'category' => 'Routers y Access Point'],
            ['cod' => '5609', 'name' => 'ROUTER TP-LINK ER605 VPN OMADA 5 PUERTOS MULTI-WAN', 'category' => 'Routers y Access Point'],
            ['cod' => '7994', 'name' => 'SISTEMA WIFI DE MALLA LINKSYS VELOP VLP0102CA AC2400 WIFI (2PACK)', 'category' => 'Routers y Access Point'],
            ['cod' => '9540', 'name' => 'ROUTER HIKVISION DS-3WR12C AC1200 / WIRELESS WIFI 4 / 4 ANTENAS DE ALTA GANANCIA DUAL BAND', 'category' => 'Routers y Access Point'],
            ['cod' => '9539', 'name' => 'ROUTER HIKVISION DS-3WR12GC AC1200 / WIRELESS WIFI 5 / 4 ANTENAS DE ALTA GANANCIA DUAL BAND (CON PUERTO GIGABIT)', 'category' => 'Routers y Access Point'],
            ['cod' => '9538', 'name' => 'ROUTER HIKVISION DS-3WR15X / WIRELESS WIFI 6 / GIGABIT DUAL / 4 ANTENAS DE ALTA GANANCIA (1500MBPS)', 'category' => 'Routers y Access Point'],
            ['cod' => '9535', 'name' => 'ROUTER HIKVISION DS-3WR3N / WIRELESS WIFI 4 / 2 ANTENAS DE ALTA GANANCIA (300MBPS)', 'category' => 'Routers y Access Point'],
            ['cod' => '9537', 'name' => 'ROUTER HIKVISION DS-3WR18X / WIRELESS WIFI 6 / GIGABIT DUAL / 5 ANTENAS DE ALTA GANANCIA (1800MBPS)', 'category' => 'Routers y Access Point'],
            ['cod' => '9536', 'name' => 'ROUTER HIKVISION DS-3WR30X / WIRELESS WIFI 6 / GIGABIT DUAL / 5 ANTENAS DE ALTA GANANCIA (3000MBPS)', 'category' => 'Routers y Access Point'],

            // SWITCH
            ['cod' => '3603', 'name' => 'SWITCH MERCUSYS 5 PUERTOS 10/1000 MS105G GIGABIT', 'category' => 'Switches de Red'],
            ['cod' => '9534', 'name' => 'SWITCH HIKVISION DS-3E0105D-O / 5 PUERTOS 10/100 MBPS (MDI/MDIX) PLASTICO', 'category' => 'Switches de Red'],
            ['cod' => '9530', 'name' => 'SWITCH HIKVISION GIGABIT DS-3E0508D-O / 8 PUERTOS 10/100/1000 MBPS (MDI/MDIX) PLASTICO', 'category' => 'Switches de Red'],
            ['cod' => '9529', 'name' => 'SWITCH HIKVISION GIGABIT DS-3E0508-O / 8 PUERTOS 1000 MBPS (MDI/MDIX) METAL', 'category' => 'Switches de Red'],
            ['cod' => '9528', 'name' => 'SWITCH HIKVISION GIGABIT DS-3E0516-E(C) / 16 PUERTOS 10/100/1000 MBPS / MONTAJE RACK / (MDI/MDIX) METAL', 'category' => 'Switches de Red'],
            ['cod' => '9527', 'name' => 'SWITCH HIKVISION GIGABIT DS-3E0524-E(C) / 24 PUERTOS 10/100/1000 MBPS / MONTAJE RACK / (MDI/MDIX) METAL', 'category' => 'Switches de Red'],
            ['cod' => '9533', 'name' => 'SWITCH HIKVISION DS-3E0108D-O / 8 PUERTOS 10/100 MBPS (MDI/MDIX) PLASTICO', 'category' => 'Switches de Red'],
            ['cod' => '9531', 'name' => 'SWITCH HIKVISION GIGABIT DS-3E0505-O / 5 PUERTOS 1000 MBPS (MDI/MDIX) METAL', 'category' => 'Switches de Red'],

            // SILLAS
            ['cod' => '8874', 'name' => 'SILLA EJECUTIVA GIRATORIA ERGONOMICA (CON APOYABRAZOS) 804 NEGRA', 'category' => 'Sillas'],
            ['cod' => '8632', 'name' => 'SILLA INFINYTEK DC-103 EJECUTIVA GIRATORIA ERGONOMICA (CON APOYABRAZOS)', 'category' => 'Sillas'],
            ['cod' => '8633', 'name' => 'SILLA INFINYTEK DC-156 EJECUTIVA GIRATORIA ERGONOMICA (CON APOYABRAZOS Y APOYACABEZA)', 'category' => 'Sillas'],
            ['cod' => '3840', 'name' => 'SILLA EJECUTIVA GIRATORIA RISTRETTO BLACK', 'category' => 'Sillas'],
            ['cod' => '8619', 'name' => 'SILLA GAMER MARVO CH-174 NARANJA APOYO LUMBAR', 'category' => 'Sillas'],
            ['cod' => '8618', 'name' => 'SILLA GAMER MARVO CH-174 NEGRA APOYO LUMBAR', 'category' => 'Sillas'],
            ['cod' => '8620', 'name' => 'SILLA GAMER MARVO CH-174 ROJA APOYO LUMBAR', 'category' => 'Sillas'],
            ['cod' => '8634', 'name' => 'SILLA INFINYTEK OC-2208W EJECUTIVA GIRATORIA ERGONOMICA (CON APOYABRAZOS Y APOYACABEZA)', 'category' => 'Sillas'],

            // ACCESORIOS TABLET / LAPIZ OPTICO
            ['cod' => '4444', 'name' => 'STAND LAPTOP TABLET CELULAR QUASAD UP-1S METALICO SILVER', 'category' => 'Accesorios Tablet'],
            ['cod' => '5515', 'name' => 'LAPIZ OPTICO STYLUS PEN PARA TABLET CELULAR', 'category' => 'Accesorios Tablet'],
            ['cod' => '7785', 'name' => 'LAPIZ OPTICO STYLUS PEN PARA PANTALLAS TACTILES PLATEADO', 'category' => 'Accesorios Tablet'],
            ['cod' => '9493', 'name' => 'LAPIZ OPTICO STYLUS PEN PARA PANTALLAS TACTILES NEGRO', 'category' => 'Accesorios Tablet'],

            // TABLETS
            ['cod' => '10136', 'name' => 'TABLET PROSPER G11 2.0GHZ 4GB 64GB 10.1INCH WIFI 4G-LTE BT USB-C 2-CAM AND13 BLACK', 'category' => 'Tablets'],
            ['cod' => '2511', 'name' => 'TABLET SAMSUNG T295 TAB A 8.0 WXGA Tft 2gb (RAM) + 32gb (ROM) MEMORIA (4g Lte) 5100 Mah Black CAMARA 8mp Af + 2mp Android 11 GRATIS MICRO SD 128GB', 'category' => 'Tablets'],
            ['cod' => '7518', 'name' => 'TABLET XIAOMI REDMI PAD SE 11 4+128 GB GREY (AMERICANO)', 'category' => 'Tablets'],
            ['cod' => '9336', 'name' => 'ESTUCHE PARA TABLET XIAOMI PAD YOUULAR FUNDA PARA TABLET XIAOMI REDMI PAD SE 11.0 PULGADAS CUBIERTA A PRUEBA DE GOLPES CON SOPORTE GIRATORIO DE 360', 'category' => 'Tablets'],
            ['cod' => '5671', 'name' => 'TABLET APPLE IPAD AIR WI-FI 64GB BLUE (5TH GEN) 10.9 (ULTIMO MODELO)', 'category' => 'Tablets'],

            // ADAPTADOR DE RED / BLUETOOTH
            ['cod' => '10370', 'name' => 'ADAPTADOR DE RED MINI USB CONEXXIS ZF-HS92 2.4GHZ 300MBPS', 'category' => 'Adaptadores de Red'],
            ['cod' => '3604', 'name' => 'ADAPTADOR DE RED MERCUSYS MW300UM USB ADAPTER 300N 300MBPS', 'category' => 'Adaptadores de Red'],
            ['cod' => '3402', 'name' => 'ADAPTADOR DE RED USB A WIFI TP-LINK ARCHER T2U PLUS AC600 DUAL BAND 1 ANTENA WIFI', 'category' => 'Adaptadores de Red'],
            ['cod' => '5840', 'name' => 'ADAPTADOR DE RED USB A RJ45 TP-LINK UE300 3.0 10/100/1000', 'category' => 'Adaptadores de Red'],
            ['cod' => '4361', 'name' => 'ADAPTADOR DE RED USB A RJ45 TP-LINK UE330 + 3 USB 3.0 10/100/1000', 'category' => 'Adaptadores de Red'],
            ['cod' => '4270', 'name' => 'ADAPTADOR DE RED USB A RJ45 S-USB 3.0 RJ45 10/100/1000', 'category' => 'Adaptadores de Red'],

            // TARJETA DE RED INTERNA
            ['cod' => '688', 'name' => 'TARJETA DE RED TP-LINK PCI-EXP. TL-WN881ND 2 ANTENAS', 'category' => 'Tarjetas de Red'],
            ['cod' => '515', 'name' => 'TARJETA DE RED ANERA PCI AE-LP8169 10/100/1000', 'category' => 'Tarjetas de Red'],
            ['cod' => '9581', 'name' => 'TARJETA DE RED LB-LINK P650H PCI EXPRESS WIRELESS 650 Mbps 2 ANTENAS', 'category' => 'Tarjetas de Red'],
            ['cod' => '516', 'name' => 'TARJETA DE RED PCI EXP. ANERA AE-LPE811E 10/100/1000', 'category' => 'Tarjetas de Red'],

            // TARJETA DE SONIDO
            ['cod' => '590', 'name' => 'TARJETA DE SONIDO ANERA 6 CANALES PCI INTERNA AE-SCPE6', 'category' => 'Tarjetas de Red'],
            ['cod' => '2862', 'name' => 'TARJETA DE SONIDO /USB 8.1 2 ENTRADAS EXT.', 'category' => 'Tarjetas de Red'],

            // TARJETA DE VIDEO
            ['cod' => '10353', 'name' => 'TARJETA DE VIDEO MSI - GEFORCE RTX 3050 GAMING X 6G - PCI EXPRESS 4.0 X16 (X8 MODE) - NVIDIA - GDDR6 SDRAM - HDMI / DISPLAYPORT', 'category' => 'Tarjetas de Video'],
            ['cod' => '8807', 'name' => 'TARJETA DE VIDEO 6GB ASUS DUAL GEFORCE OC-6GD RTX 3050 GDDR6 PCI', 'category' => 'Tarjetas de Video'],
            ['cod' => '3683', 'name' => 'CAPTURADORA DE VIDEO CORSAIR EL GATO HD60 S TRANSMISION & GRABACION 1080p-60FPS', 'category' => 'Tarjetas de Video'],
            ['cod' => '6090', 'name' => 'TARJETA DE VIDEO 12GB MSI RTX 4070 Ti GAMING X3 TRIO', 'category' => 'Tarjetas de Video'],
            ['cod' => '7030', 'name' => 'TARJETA DE VIDEO 16GB GIGABYTE AOURUS RX 6900XT XTREME WATHERFORCE WB DDR6 HDMI x2 DP x2 4K UHD', 'category' => 'Tarjetas de Video'],

            // TECLADOS
            ['cod' => '6008', 'name' => 'TECLADO INFINYTEK SC744 USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '10276', 'name' => 'TECLADO XTRATECH XTR-271BK ALAMBRICO USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '9821', 'name' => 'TECLADO XTECH XTK-092S USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '3083', 'name' => 'TECLADO+MOUSE XTECH XTK-301S USB', 'category' => 'Teclados'],
            ['cod' => '8954', 'name' => 'TECLADO + MOUSE + PAD MOUSE XTECH XTK-535S GAMING USB ESPAÑOL', 'category' => 'Teclados'],
            ['cod' => '5695', 'name' => 'TECLADO XTECH MARVEL IRON MAN USB XTKM401IM EDICION ESPECIAL', 'category' => 'Teclados'],
            ['cod' => '5696', 'name' => 'TECLADO XTECH MARVEL CAPITAN AMERICA USB XTKM401CA EDICION ESPECIAL', 'category' => 'Teclados'],
            ['cod' => '9605', 'name' => 'TECLADO + MOUSE INFINYTEK WIRELESS INALAMBRICO PILAS AA COLOR NEGRO', 'category' => 'Teclados'],
            ['cod' => '5781', 'name' => 'TECLADO GENIUS + MOUSE KM8101 INHALAMBRICO PILAS AA X 2', 'category' => 'Teclados'],
            ['cod' => '1835', 'name' => 'TECLADO GENIUS SMART KB-100 USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '3488', 'name' => 'TECLADO GENIUS KB-116 USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '4998', 'name' => 'TECLADO GENIUS KB-117 USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '7926', 'name' => 'TECLADO KLIPX NUMERICO KNP100 USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '5684', 'name' => 'TECLADO KLIPX NUMERICO KNP110 INALAMBRICO', 'category' => 'Teclados'],
            ['cod' => '3085', 'name' => 'TECLADO GENIUS SLIMSTAR 126 USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '3353', 'name' => 'TECLADO + MOUSE+ PARLANTES SPEEDMIND K3N102 USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '8936', 'name' => 'TECLADO GENIUS + MOUSE KM8206S INHALAMBRICO', 'category' => 'Teclados'],
            ['cod' => '8475', 'name' => 'TECLADO GENIUS ERGONOMICO ERGO KB-700 NEGRO USB TECLA COPILOT MULTIMEDIA REPOSAMANOS WIN 10 11 MAC OS 10', 'category' => 'Teclados'],
            ['cod' => '4225', 'name' => 'TECLADO LOGITECH K270 INHALAMBRICO', 'category' => 'Teclados'],
            ['cod' => '8239', 'name' => 'TECLADO LOGITECH MK120 + MOUSE USB NEGRO', 'category' => 'Teclados'],
            ['cod' => '10254', 'name' => 'TECLADO MARVO CM416 +MOUSE+AUDIFONO USB/3.5MM +PAD MOUSE RGB NEGRO', 'category' => 'Teclados'],
            ['cod' => '10262', 'name' => 'TECLADO XTRIKE ME KB-309-BK 3COLORES MEZCLADOS 102 TECLAS SP USB', 'category' => 'Teclados'],
        ];

        $created = 0;
        foreach ($products as $product) {
            $categoryId = $categoryIds[$product['category']] ?? null;
            if (!$categoryId) {
                $this->command->warn("Categoría no encontrada: {$product['category']}");
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
                'price_base' => rand(10, 500) + (rand(0, 99) / 100),
                'is_active' => true,
                'is_featured' => rand(0, 10) > 8,
            ]);
            $created++;
        }

        $this->command->info("✅ Tercera parte - Productos creados: {$created}");
    }
}
