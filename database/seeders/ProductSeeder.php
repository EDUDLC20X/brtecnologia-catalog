<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Crear productos reales de B&R Tecnología
     */
    public function run(): void
    {
        // Limpiar productos existentes
        Product::truncate();
        Category::truncate();

        // Crear todas las categorías necesarias
        $categories = [
            'Adaptadores' => 'Adaptadores y convertidores de video y conectividad',
            'AIO / Todo en Uno' => 'Computadores All-in-One InfinyTek',
            'Herramientas y Limpieza' => 'Herramientas técnicas y productos de limpieza',
            'Audífonos y Headphones' => 'Audífonos, headphones y accesorios de audio',
            'Baterías para Portátil' => 'Baterías de reemplazo para laptops',
            'Cartuchos y Cabezales' => 'Cartuchos y cabezales para impresoras',
            'Cables de Video' => 'Cables HDMI, DisplayPort y convertidores',
            'Cables y Conectores' => 'Cables USB, UTP, conectores RJ45',
            'Cámaras de Vigilancia' => 'Cámaras IP, DVR y sistemas de seguridad',
            'Cargadores' => 'Cargadores para laptops, celulares y dispositivos',
            'Cases y Enclosures' => 'Cases para disco duro y almacenamiento externo',
            'Cases para PC' => 'Chasis y combos para computadoras',
            'Cases Gamer' => 'Cases gaming con iluminación RGB',
            'CPU Ensamblados' => 'Computadores de escritorio ensamblados',
            'Discos Duros' => 'Discos duros internos y externos',
            'Discos Sólidos SSD' => 'Unidades de estado sólido SSD y NVMe',
            'DVD Writer' => 'Unidades ópticas externas',
            'Estuches y Mochilas' => 'Estuches, maletas y mochilas para laptops',
            'Electrodomésticos' => 'Refrigeradoras, lavadoras y electrodomésticos',
            'Extensores WiFi' => 'Repetidores y extensores de señal WiFi',
            'Flash Memory' => 'Memorias USB y pendrive',
            'Fuentes de Poder' => 'Fuentes ATX y certificadas para PC',
            'Hubs USB' => 'Hubs y adaptadores multipuerto',
            'Impresoras' => 'Impresoras de inyección, láser y multifunción',
            'Lectores de Código' => 'Lectores de código de barras y escáneres',
            'Licencias Software' => 'Licencias Windows, Office y antivirus',
            'Mainboards' => 'Tarjetas madre Intel y AMD',
            'Memorias MicroSD' => 'Tarjetas de memoria microSD',
            'Memorias RAM' => 'Memorias RAM DDR3, DDR4 y DDR5',
            'Mesas y Escritorios' => 'Mesas de vidrio y extensiones para escritorio',
            'Monitores' => 'Monitores LED, gaming y profesionales',
            'Mouse' => 'Mouse alámbricos e inalámbricos',
            'Pad Mouse' => 'Alfombrillas y pad mouse ergonómicos',
            'Parlantes' => 'Parlantes, soundbars y sistemas de audio',
            'Pantallas para Portátil' => 'Pantallas de reemplazo para laptops',
            'Papel y Suministros' => 'Papel bond, térmico y fotográfico',
            'Procesadores' => 'Procesadores Intel y AMD',
            'Proyectores' => 'Proyectores y pantallas de proyección',
            'Protectores y Reguladores' => 'Reguladores de voltaje y supresores de picos',
            'Smart Watch' => 'Relojes inteligentes y wearables',
            'Escáneres' => 'Escáneres de documentos',
            'Routers y Access Point' => 'Routers WiFi y access points',
            'Switches de Red' => 'Switches Ethernet y Gigabit',
            'Sillas' => 'Sillas ejecutivas y gaming',
            'Accesorios Tablet' => 'Lápices ópticos, stands y accesorios',
            'Tablets' => 'Tablets Android, iPad y accesorios',
            'Adaptadores de Red' => 'Adaptadores WiFi y Ethernet USB',
            'Tarjetas de Red' => 'Tarjetas de red PCI y PCIe',
            'Tarjetas de Video' => 'GPUs NVIDIA y AMD',
            'Teclados' => 'Teclados USB, inalámbricos y gaming',
            'Tintas y Tóners' => 'Tintas originales y genéricas',
            'Televisores' => 'Smart TV LED, QLED y accesorios',
            'TV Box' => 'Dispositivos de streaming',
            'Generadores' => 'Generadores eléctricos a gasolina y diesel',
            'Power Station' => 'Estaciones de energía portátil y paneles solares',
            'UPS' => 'Sistemas de energía ininterrumpida',
            'Power Bank' => 'Baterías portátiles y cargadores externos',
            'Ventiladores PC' => 'Ventiladores y coolers para PC',
            'Productos Amazon' => 'Dispositivos Alexa y Echo',
        ];

        $categoryIds = [];
        foreach ($categories as $name => $description) {
            $cat = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
                'is_active' => true,
            ]);
            $categoryIds[$name] = $cat->id;
        }

        $this->command->info('✅ Categorías creadas: ' . count($categoryIds));

        // PRIMERA MITAD DE PRODUCTOS
        $products = [
            // ADAPTADORES
            ['cod' => '7816', 'name' => 'ADAPTADOR OTG MICROUSB A USB', 'category' => 'Adaptadores'],
            ['cod' => '1624', 'name' => 'ADAPTADOR DVI A HDMI', 'category' => 'Adaptadores'],
            ['cod' => '2527', 'name' => 'CONVERTIDOR VGA A HDMI AUDIO Y ALIMENTACION', 'category' => 'Adaptadores'],

            // AIO / TODO EN UNO INFINYTEK BAREBONE
            ['cod' => '9599', 'name' => 'AIO InfinyTek "Genesis 5" 243AIOPC (i3 13gen) i3-13100 - BAREBONE', 'category' => 'AIO / Todo en Uno'],
            ['cod' => '9603', 'name' => 'AIO InfinyTek "Genesis 5" 243AIOPC (i5 14gen) i5-14400 - BAREBONE', 'category' => 'AIO / Todo en Uno'],
            ['cod' => '9600', 'name' => 'AIO InfinyTek "Genesis 7" 273AIOPC (i3 13gen) i3-13100 - BAREBONE', 'category' => 'AIO / Todo en Uno'],
            ['cod' => '9602', 'name' => 'AIO InfinyTek "Genesis 7" 273AIOPC (Ultra 5) ULTRA 5-125H - BAREBONE', 'category' => 'AIO / Todo en Uno'],

            // HERRAMIENTAS / ACCESORIOS LIMPIEZA
            ['cod' => '8941', 'name' => 'AIRE COMPRIMIDO ANERA AE-DUSA01 400ML', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '3179', 'name' => 'AIRE COMPRIMIDO ABRO AD-237 400ML (8OZ)', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '9703', 'name' => 'ALCOHOL ISOPROPILICO BLOW OFF SPRAY 1.4 ONZ', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '7901', 'name' => 'ALCOHOL ISOPROPILICO BLOW OFF SPRAY 12ONZ', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '9494', 'name' => 'MANTA ANTIESTATICA NITRON 450*300MM NITRON S-160', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '935', 'name' => 'CONECTOR DE DISCO DURO PARA HP', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '8328', 'name' => 'DESTORNILLADOR ELECTRICO INALAMBRICO XIAOMI DZN4019TW KIT 12PZ CARGA TIPO C', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '8327', 'name' => 'DESTORNILLADOR ELECTRICO DE PRECISION XIAOMI BHR5474GL KIT 20PZ CARGA TIPO C', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '1869', 'name' => 'ESPUMA LIMPIADORA OPLA 650ML', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '3178', 'name' => 'LIMPIADOR DE CONTACTOS ELECT ABRO ECB-833 10 ONZ', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '154', 'name' => 'LIMPIADOR DE PANTALLA DE LAPTOP SPRAY BROCHA / TOALLA LC01', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '2594', 'name' => 'PASTA TERMICA NITRON COMPONENTES 30G', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '8189', 'name' => 'PILA PARA MAINBOARD CR2032 RCA', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '251', 'name' => 'PILA PARA MAINBOARD CR2032 JAPONESA', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '2449', 'name' => 'PICO PSU 90W', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '1979', 'name' => 'PONCHADORA UNIVERSAL KOION RJ45/RJ12/RJ11 CON CORTADORA', 'category' => 'Herramientas y Limpieza'],
            ['cod' => '3719', 'name' => 'SOPLADORA DE MANO 110V 650W VERDE DW50100', 'category' => 'Herramientas y Limpieza'],

            // AUDIFONOS / HEADPHONES
            ['cod' => '10092', 'name' => 'AUDIFONOS JBL TUNE 110 T110 INEAR BY HARMAN MANOS LIBRES 3.5MM BLUE', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9040', 'name' => 'SOPORTE GENIUS GX-UH100 RGB PARA AURICULAR GAMING STAND PUERTOS USB A Y 2 USB C', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9731', 'name' => 'HEADPHONE ELASSER HD-454L 3.5MM NEGROS', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9324', 'name' => 'AUDIFONOS APPLE AIRPODS PRO USB-C (2DA GEN) - WHITE', 'category' => 'Audífonos y Headphones'],
            ['cod' => '10093', 'name' => 'AUDIFONO INFINIX XBUDS 3 LITE WHITE - MODEL XE33', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9779', 'name' => 'AUDIFONO SKULLCANDY SMOKIN BUDS IN-EAR WIRELESS EARBUDS 20 HR BATTERY BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9247', 'name' => 'HEADPHONE KLIPX KCH-905 ONEAR VOLMIC WIRELESS BT MAGNETIC', 'category' => 'Audífonos y Headphones'],
            ['cod' => '10099', 'name' => 'AUDIFONO SOUNCORE P40i CARGA 12H/60H CANCELACION DE RUIDO BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '10100', 'name' => 'AUDIFONO SOUNDCORE TWS SPORT X20 CANCELACION DE RUIDO ANC BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '8190', 'name' => 'HEADPHONE HP DHE-8001 + MICROFONO USB', 'category' => 'Audífonos y Headphones'],
            ['cod' => '5692', 'name' => 'AUDIFONO XTECH CAPITANA MARVEL CON MICROFONO TEM100CM EDICION ESPECIAL', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9017', 'name' => 'HEADPHONE XTECH XTH-565 WIRED 3.5MM USB POWER', 'category' => 'Audífonos y Headphones'],
            ['cod' => '8930', 'name' => 'HEADPHONE XTECH MANOS LIBRES PINK XTH355', 'category' => 'Audífonos y Headphones'],
            ['cod' => '5694', 'name' => 'HEADPHONE XTECH MARVEL CAPITAN AMERICA BLUETOOTH XTHD660CA', 'category' => 'Audífonos y Headphones'],
            ['cod' => '5693', 'name' => 'HEADPHONE XTECH MARVEL IRON MAN BLUETOOTH XTHD660IM', 'category' => 'Audífonos y Headphones'],
            ['cod' => '5689', 'name' => 'HEADPHONE XTECH DISNEY MICKEY MOUSE BLUETOOTH XTHD660MK EDICION ESPECIAL', 'category' => 'Audífonos y Headphones'],
            ['cod' => '6265', 'name' => 'AUDIFONOS JBL TUNE 110 T110 INEAR BY HARMAN MANOS LIBRES 3.5MM BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '5163', 'name' => 'HEADPHONE JBL TUNE 500 CON DIADEMA CON MICRO 3.5MM NEGRO', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9776', 'name' => 'HEADPHONE JBL QUANTUM 100 WIRED OVER-EAR GAMING HEADPHONES BLACK LARGE', 'category' => 'Audífonos y Headphones'],
            ['cod' => '8882', 'name' => 'AUDIFONOS JBL ENDURANCE RUN 2 INALAMBRICOS INTERNOS CON MICRO EN OREJA BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '7488', 'name' => 'HEADPHONE JBL TUNE 660 NC WIRELESS ON EAR BLUE', 'category' => 'Audífonos y Headphones'],
            ['cod' => '8541', 'name' => 'AUDIFONO GENIUS HS-M905BT NEGRO INALAMBRICOS BLUETOOTH 5.0 ESTUCHE DE CARGA TYPE C', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9956', 'name' => 'AUDIFONO CON MICROFONO GENIUS HS-M365 IN-EAR USB-C NEGRO', 'category' => 'Audífonos y Headphones'],
            ['cod' => '1864', 'name' => 'HEADPHONE GENIUS HSM200C NEGRO CON MICROFONO CONECTOR 3.5MM', 'category' => 'Audífonos y Headphones'],
            ['cod' => '3260', 'name' => 'HEADPHONE GENIUS HS04S CON MICROFONO CONECTOR 3.5 MM', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9957', 'name' => 'HEADPHONE GENIUS GAMING HS-810BT BLUETOOH NEGRO', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9312', 'name' => 'HEADPHONE GENIUS HS-100U CON MICROFONO MONO USB BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '6192', 'name' => 'HEADPHONE GENIUS HS-230U CON MICROFONO BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '554', 'name' => 'HEADPHONE LOGITECH USB H390 CON MICROFONO BLACK', 'category' => 'Audífonos y Headphones'],
            ['cod' => '9838', 'name' => 'HEADPHONE AXTEL ONE AXH-ONEUCD CONECTIVIDAD USB CONTROL LLAMADAS MICROFONO CON ANULACION DE RUIDO', 'category' => 'Audífonos y Headphones'],
            ['cod' => '10098', 'name' => 'HEADPHONE ANKER SOUNDCORE H30i BATERIA 70H WHITE', 'category' => 'Audífonos y Headphones'],

            // BATERIAS PARA PORTATIL
            ['cod' => '1525', 'name' => 'BATERIA PARA PORTATIL DELL 14Z 2NJNF', 'category' => 'Baterías para Portátil'],
            ['cod' => '2872', 'name' => 'BATERIA PARA PORTATIL DELL TYPE TRHFF', 'category' => 'Baterías para Portátil'],
            ['cod' => '4933', 'name' => 'BATERIA PARA PORTATIL DELL G5M10 INTERNA 7.4V', 'category' => 'Baterías para Portátil'],
            ['cod' => '9352', 'name' => 'BATERIA PARA PORTATIL DELL LATITUDE 3410 3510 VOSTRO 5300 5401 5501 TYPE H5CKD INTERNA ORIGINAL', 'category' => 'Baterías para Portátil'],
            ['cod' => '3026', 'name' => 'BATERIA PARA PORTATIL HP COMPAC CQ10 110-3000', 'category' => 'Baterías para Portátil'],
            ['cod' => '2513', 'name' => 'BATERIA PARA PORTATIL HP 6520 / DU06', 'category' => 'Baterías para Portátil'],
            ['cod' => '5872', 'name' => 'BATERIA PARA PORTATIL LENOVO B40 B50 L13M4A01 LEM4450-4BK ORIGINAL', 'category' => 'Baterías para Portátil'],
            ['cod' => '503', 'name' => 'BATERIA PARA PORTATIL SONY M06500', 'category' => 'Baterías para Portátil'],
            ['cod' => '356', 'name' => 'BATERIA PARA PORTATIL TOSHIBA KS-E64011B', 'category' => 'Baterías para Portátil'],

            // CARTUCHOS / CABEZAL DE IMPRESORAS
            ['cod' => '4212', 'name' => 'CARTUCHO EPSON T133420 YELLOW T22 25 TX120 123 125 130 133 135 235W 420W 430W TX320F', 'category' => 'Cartuchos y Cabezales'],
            ['cod' => '3509', 'name' => 'CABEZAL CANON COLOR/BLACK BH1/CH1 G1110 G2100 G2101 G2110 G2111 G3100 G3101 G3110 G3111 G4100 G4102 G4110 G4111', 'category' => 'Cartuchos y Cabezales'],
            ['cod' => '7373', 'name' => 'CABEZAL HP TRICOLOR 3YP17AL SERIES HP 670 SERIES 700 HP 720 HP750 HP790 WL-580', 'category' => 'Cartuchos y Cabezales'],
            ['cod' => '6307', 'name' => 'CARTUCHO HP L0S65AL (954XL) MAGENTA COMPATIBLE 8210 7720 8710 8720 8730 8740', 'category' => 'Cartuchos y Cabezales'],

            // CABLES DE VIDEO / CONVERTIDORES
            ['cod' => '10', 'name' => 'CABLE DE DATOS PARA DISCO SATA', 'category' => 'Cables de Video'],
            ['cod' => '359', 'name' => 'CABLE DE PODER PARA DISCO SATA', 'category' => 'Cables de Video'],
            ['cod' => '12', 'name' => 'CABLE DE PODER', 'category' => 'Cables de Video'],
            ['cod' => '884', 'name' => 'CABLE DE PODER 2 VÍAS', 'category' => 'Cables de Video'],
            ['cod' => '8357', 'name' => 'CABLE DE PODER TREBOL 1.5 MTRS REDONDO', 'category' => 'Cables de Video'],
            ['cod' => '216', 'name' => 'CABLE DE PODER TREBOL 1.8 MTRS REDONDO', 'category' => 'Cables de Video'],
            ['cod' => '2863', 'name' => 'CABLE EN Y 3.5 STEREO A 2 JACK 3.5 STEREO', 'category' => 'Cables de Video'],
            ['cod' => '9480', 'name' => 'CABLE CONVERTIDOR DE VOLTAJE PARA ROUTER DE 5V A 12V (COMPATIBLE CON POWER-BANKS)', 'category' => 'Cables de Video'],
            ['cod' => '2709', 'name' => 'CABLE DELTA MINI DISPLAY A HDMI HEMBRA 20CM', 'category' => 'Cables de Video'],
            ['cod' => '939', 'name' => 'CONVERTIDOR DE USB A VGA AE-USB 3.0V02', 'category' => 'Cables de Video'],
            ['cod' => '10097', 'name' => 'COMBO DE CABLES INFINIX FLAT CABLE CAN 30 UNITS USB-A TO USB-C 1M (5 COLORS)', 'category' => 'Cables de Video'],
            ['cod' => '10096', 'name' => 'COMBO DE CABLES INFINIX FLAT CABLE CAN 30 UNITS USB-C TO USB-C 60W 1M (5 COLORS)', 'category' => 'Cables de Video'],
            ['cod' => '2124', 'name' => 'CABLE DE PODER TREBOL 1.5 MTRS PLANO', 'category' => 'Cables de Video'],
            ['cod' => '1972', 'name' => 'CABLE HDMI A HDMI 1.5MTRS HIGH SPEED 55682', 'category' => 'Cables de Video'],
            ['cod' => '4621', 'name' => 'CABLE HDMI A HDMI NEGRO 1.5 MTRS 4K HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '28', 'name' => 'CABLE HDMI A HDMI NEGRO 3 MTRS', 'category' => 'Cables de Video'],
            ['cod' => '4617', 'name' => 'CABLE HDMI A HDMI NEGRO 3 MTRS HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '1973', 'name' => 'CABLE HP HDMI A HDMI 3MTRS HIGH SPEED 55683', 'category' => 'Cables de Video'],
            ['cod' => '1537', 'name' => 'CABLE HDMI A HDMI NEGRO 3 MTRS 4K HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '10113', 'name' => 'CABLE HDMI A HDMI NEGRO 5 MTRS HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '4618', 'name' => 'CABLE HDMI A HDMI NEGRO 5 MTRS 4K HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '10112', 'name' => 'CABLE HDMI A HDMI NEGRO 10 MTRS HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '7917', 'name' => 'CABLE HDMI A HDMI NEGRO 15 MTRS HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '4232', 'name' => 'CABLE HDMI A HDMI NEGRO 15 MTRS 4K HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '493', 'name' => 'CABLE CONVERTIDOR HDMI A VGA', 'category' => 'Cables de Video'],
            ['cod' => '6178', 'name' => 'CABLE CONVERTIDOR DISPLAY PORT A HDMI DELTA 20CM', 'category' => 'Cables de Video'],
            ['cod' => '4198', 'name' => 'CABLE HDMI A HDMI NEGRO 20 MTRS 4K HIGH SPEED', 'category' => 'Cables de Video'],
            ['cod' => '33', 'name' => 'CABLE IMPRESORA USB 2.0 1.8MTS', 'category' => 'Cables y Conectores'],
            ['cod' => '108', 'name' => 'EXTENSION USB MACHO A HEMBRA 1.8MTRS', 'category' => 'Cables y Conectores'],

            // CABLE UTP / CONECTORES
            ['cod' => '83', 'name' => 'CUBIERTA PARA CONECTOR RJ 45', 'category' => 'Cables y Conectores'],
            ['cod' => '8129', 'name' => 'CONECTOR RJ45 CAT 5e PLASTICO HD-8P8C-RJ45-U15', 'category' => 'Cables y Conectores'],
            ['cod' => '825', 'name' => 'INOUXX', 'category' => 'Cables y Conectores'],
            ['cod' => '79', 'name' => 'CONECTOR RJ45 CAT 5 BLINDADO', 'category' => 'Cables y Conectores'],
            ['cod' => '5498', 'name' => 'CONECTOR RJ45 CAT 6 BLINDADO', 'category' => 'Cables y Conectores'],
            ['cod' => '36', 'name' => 'CABLE UTP CAT5 DEFENDER METROS', 'category' => 'Cables y Conectores'],
            ['cod' => '4290', 'name' => 'CABLE UTP CAT6 DEFENDER METROS', 'category' => 'Cables y Conectores'],
            ['cod' => '5034', 'name' => 'CAJA DE MONTAJE NEXXT AE180NXT05 SUPERFICIE DE RED BLANCO', 'category' => 'Cables y Conectores'],
            ['cod' => '3629', 'name' => 'ROLLO DE CABLE - UTP ANERA AE-CAT5E 305M 050MM 80CCA 20CU 24AWG BEIGE', 'category' => 'Cables y Conectores'],
            ['cod' => '1854', 'name' => 'ROLLO DE CABLE - UTP ANERA AE-CAT5E 305M 050MM 80CCA 20CU 24AWG BLANCO', 'category' => 'Cables y Conectores'],
            ['cod' => '4805', 'name' => 'ROLLO DE CABLE - UTP ANERA AE-CAT6 305M 056MM 80CCA 20CU BLANCO', 'category' => 'Cables y Conectores'],
            ['cod' => '2343', 'name' => 'PATCH CORD CAT6 3 MTR AZUL', 'category' => 'Cables y Conectores'],
            ['cod' => '250', 'name' => 'PATCH CORD CAT6 5MTRS BLUE', 'category' => 'Cables y Conectores'],
            ['cod' => '5038', 'name' => 'PLACA DE MONTAJE NEXXT AW161NXT01 BLANCO 1 PUERTO', 'category' => 'Cables y Conectores'],

            // CAMARA DE VIGILANCIA
            ['cod' => '2306', 'name' => 'PLUG PARA CAMARAS', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '2305', 'name' => 'JACK PARA CAMARA', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '5859', 'name' => 'FUENTE DE PODER PARA CAMARA 12V 1.5AMP', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '568', 'name' => 'PAREJA DE VIDEO BALUN PARA CAMARA DE VIDEO 5CM', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '4310', 'name' => 'CAMARA DE VIGILANCIA HIKVISION DS-2CE56C0T-IRMF DOMO 720P TURBO HD 2.8MM METALICA', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '2204', 'name' => 'CAMARA DE VIGILANCIA HIKVISION DS-2CE16C0T-IRF BULLET 720P TUBO 20M 0.01LUX METALICA IP66', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '2777', 'name' => 'CAMARA DE VIGILANCIA HIKVISION DS-2CE56D0T-IRPF DOMO 1080P 2.8MM 20M 0.01LUX PLASTICA', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '1149', 'name' => 'CAMARA DE VIGILANCIA HIKVISION DS-2CE16D0T-IRF BULLET 1080P 2.8MM 25M 0.01LUX METALICA IP66', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '9008', 'name' => 'CAMARA DE VIDEO VIGILANCIA HIKVISION DS-2CE10KF0T-LPFS TUBO SELLADO HD-TVI 3K IR 10-20M COLORVU CON AUDIO', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '6275', 'name' => 'DVR HIKVISION DS-7208HGHI-M1 8CH 720P HASTA 1080P 1U H.265', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '5877', 'name' => 'DVR HIKVISION DS-7216HGHI-M1 TURBO HD/ 16 CANALES/HASTA 1080P H.265', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '8841', 'name' => 'DVR HIKVISION DS-7232HQHI-M2/S TURBO HD 32CH', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '8497', 'name' => 'KIT DE CAMARA DE VIGILANCIA HIKVISION HILOOK KIT NVR 4 PUERTOS HDMI/VGA/2 USB/RJ45 WIFI DISCO DURO 1TB PREINSTALADO', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '3660', 'name' => 'CAMARA DE VIDEO CONFERENCIA STARCAM CU1 MICROFONO/FHD1080P/65G/6M USB', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '2930', 'name' => 'CAMARA DE VIDEO CONFERENCIA CONEXXIS DCM141 MICROFONO/720P/170G/6M', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '992', 'name' => 'CAMARA DE VIDEO CONFERENCIA GENIUS 1000X HD/720P/USB', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '9883', 'name' => 'CAMARA HIKVISION DS-2CE76D0T-EXLPF DUAL LIGHT DOMO 2 MP 1920 x 1080 2.8MM', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '9777', 'name' => 'CAMARA DE VIGILANCIA TPLINK TAPO C202 2MP VISION NOCTURNA AUDIO DOBLE VIA MICROSD 512GB DETECTOR DE MOVIMIENTO ETHERNET', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '6215', 'name' => 'CAMARA DE VIGILANCIA SMART NEXXT AHIMPFI4U1 VISION NOCTURA PLASTICA', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '10366', 'name' => 'CAMARA DE VIDEOVIGILANCIA TPLINK TAPO C212/2K/VISION NOCTURNA/AUDIO DOBLE VIA/MICROSD 512GB/DETEC. DE MOV/ETHERNET', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '4362', 'name' => 'CAMARA DE VIGILANCIA TP-LINK TAPO C310 3MP HD WIFI Y ALAMBRICO HASTA MICRO SD 512GB 104 ALTAVOCES Y MICROFONO INCORPORADO', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '9216', 'name' => 'CAMARA DE VIGILANCIA TP-LINK TAPO C510W 360 2K HIGH DEFINITION OUTDOOR SOPORTA HASTA MICRO SD 512GB', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '9908', 'name' => 'CAMARA DE VIDEO VIGILANCIA TP-LINK VIGI-C340-W 4MP OUTDOOR FULL-COLOR WI-FI BULLET NETWORK WHITE', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '8036', 'name' => 'CAMARA DE VIGILANCIA IP EZVIZ H8C-R100-1K2W2MP 360 GRADOS IP65 WIFI 2.4GHZ BIDIRECCIONAL SILUETA HUMANA MICRO SD Y NUBE PANEO E INCLINACION', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '8831', 'name' => 'CAMARA DE VIGILANCIA IP EZVIZ H6 3K WIFI 5 AUDIO BI SOPORTA MICRO SD Y NUBE VISION NOCHE COLOR', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '8033', 'name' => 'CAMARA DE VIGILANCIA IP EZVIZ BC2-A0-2C2WPF MINI CON BATERIA 1080P WIFI 2.4GHZ AUDIO 2 VIAS SOPORTA MICROSD Y NUBE ALARMA INTERNA', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '9778', 'name' => 'CAMARA DE VIGILANCIA EZVIZ H9C 2K Y 2K EXTERIOR DOBLE LENTE V. NOC. COLOR AUDIO BI HASTA 512GB D. ACTIVA', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '6958', 'name' => 'CAMARA EZVIZ H8 PRO IP 3K 360 EXT IP65 WIFI 2.4GHZ BIDIRECCIONAL MICRO SD Y NUBE DEFENSA ACT', 'category' => 'Cámaras de Vigilancia'],
            ['cod' => '8032', 'name' => 'CAMARA DE VIGILANCIA EZVIZ HB8-R100-2C4W BATERIA 10400MAH 2K AUDIO BI DETECCION Y SEGUIMIENTO DE PERSONAS VISION NOCTURNA COLOR MEMORIA INTERNA 32GB', 'category' => 'Cámaras de Vigilancia'],

            // CARGADORES
            ['cod' => '8848', 'name' => 'CARGADOR PARA CELULAR GENIUS PD-20AC 20W / DUAL USB 3.0 Y USB-C', 'category' => 'Cargadores'],
            ['cod' => '8849', 'name' => 'CARGADOR PARA CELULAR GENIUS PD-20ACP / 20W / DUAL USB 3.0 Y USB-C / CABLE C A C DE 1M', 'category' => 'Cargadores'],
            ['cod' => '9955', 'name' => 'CARGADOR PARA CELULAR GENIUS PD-65AC / 65W / USB-C WHITE', 'category' => 'Cargadores'],
            ['cod' => '9479', 'name' => 'CARGADOR PARA PORTATIL / CELULAR + CABLE KIT PHILIPS (67.5W) TIPO C (INCLUYE CABLE DE 6FT - 1.8 METROS)', 'category' => 'Cargadores'],
            ['cod' => '5585', 'name' => 'CARGADOR PARA AIO LENOVO 20V 3.25A - 00PH131 PLUG TIPO USB ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '42', 'name' => 'CARGADOR PARA IMPRESORA EPSON C825343 PS-180-343 ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '252', 'name' => 'CARGADOR PARA LAPTOP ASUS 19V 3.42A 4.0x1.35mm ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '4761', 'name' => 'CARGADOR PARA LAPTOP ASUS 19V 2.37A 5.5x2.55mm PLUG DELGADO ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '6266', 'name' => 'CARGADOR PARA LAPTOP ASUS 19V 2.37A 45W 1.35X4.0MM PUNTA FINA ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '741', 'name' => 'CARGADOR PARA LAPTOP HP 19.5V 2.31AM 45W PLUG AGUJA AZUL ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '10243', 'name' => 'CARGADOR PARA PORTATIL APPLE MACBOOK MAGSAFE 2 16.5v/3.65a/60W (T) ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '8201', 'name' => 'CARGADOR PARA LAPTOP MSI 90.1W 19.0V 4.74A PUNTA FINA NEGRO ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '8207', 'name' => 'CARGADOR PARA LAPTOP DELL 180W 19.5V 9.23A PUNTA SEMIGRUESA ROJO ORIGINAL', 'category' => 'Cargadores'],
            ['cod' => '8206', 'name' => 'CARGADOR PARA LAPTOP ASUS 120W 20.0V 6.0A PUNTA FINAL NEGRA ORIGINAL', 'category' => 'Cargadores'],

            // CASE PARA DISCO DURO
            ['cod' => '8640', 'name' => 'CASE - ENCLOSURE PARA DISCO SOLIDO M.2 SSD INFINYTEK EXTERNO NVME (CABLE TIPO C A TIPO C) NEGRO', 'category' => 'Cases y Enclosures'],
            ['cod' => '10172', 'name' => 'CASE PARA DISCO DURO (ENCLOSURE) 2.5 DELTA 3.0 PLASTICO', 'category' => 'Cases y Enclosures'],
            ['cod' => '2047', 'name' => 'CASE - ENCLOSURE PARA DISCO SOLIDO 2.5 ADATA 3.0 ED600 ANTIGOLPES', 'category' => 'Cases y Enclosures'],
            ['cod' => '5957', 'name' => 'MOCHILA CON RUEDAS PARA TRANSPORTE PortaBrace PB-2850 Wheeled Hard Case with Foam', 'category' => 'Cases y Enclosures'],

            // CASE PC / COMBO PC
            ['cod' => '8901', 'name' => 'CASE - CHASIS DE PC ALTEK MINI T100 CON FUENTE DE 875VA 345x174x340mm', 'category' => 'Cases para PC'],
            ['cod' => '2721', 'name' => 'CASE COMBO ALTEK-4300 3 USB FRONTALES AUDIO FRONTAL BOTON ENCENDIDO LED RGB BLACK INCLUYE TECLADO PARLANTE Y MOUSE', 'category' => 'Cases para PC'],
            ['cod' => '8782', 'name' => 'CASE COMBO INS CA-054 TECLADO MULTIMEDIA + MOUSE + PARLANTES + FUENTE BLACK 850WTS USB 2.0 USB 3.0 PANEL FRONTAL LED RGB', 'category' => 'Cases para PC'],

            // CASE GAMER INFINITEK
            ['cod' => '6002', 'name' => 'CASE GAMER INFINYTEK 330-6 PRO BLACK 1 VENTILADOR ARGB SIN FUENTE TAPA LATERAL VIDRIO TEMPLADO', 'category' => 'Cases Gamer'],
            ['cod' => '6003', 'name' => 'CASE GAMER INFINYTEK X-507B PRO BLACK 2 USB 2.0 1 USB 3.0 PLUG MICROFONO PLUG HEADPHONE 1 VENTILADOR ARGB SIN FUENTE TAPA LATERAL VIDRIO TEMPLADO', 'category' => 'Cases Gamer'],
            ['cod' => '6001', 'name' => 'CASE GAMER INFINYTEK H-15 PRO BLACK 4 VENTILADORES ARGB SIN FUENTE TAPA LATERAL Y FRONTAL VIDRIO TEMPLADO', 'category' => 'Cases Gamer'],
            ['cod' => '8617', 'name' => 'CASE GAMER INFINYTEK H-15 PRO WHITE 4 VENTILADORES ARGB SIN FUENTE TAPA LATERAL Y FRONTAL VIDRIO TEMPLADO', 'category' => 'Cases Gamer'],

            // CPU ENSAMBLADO Y CONFIGURADO
            ['cod' => '10303', 'name' => 'CPU DESKTOP COMPUTADOR DE ESCRITORIO INS INTEL CORE I5 12400 (12VA) ASUS H610MK RAM 8GB SSD1TB 2.5 (INCLUYE TECLADO - MOUSE & PARLANTE) ENSAMBLADO Y CONFIGURADO', 'category' => 'CPU Ensamblados'],
            ['cod' => '9926', 'name' => 'CPU DESKTOP COMPUTADOR DE ESCRITORIO GAMER INFINYTEK H-15PRO WHITE INTEL CORE I5 12400 (12VA) ASUS H610MK RAM 16GB SSD512GB M.2 WIFI TL-WN823N (INCLUYE TECLADO & MOUSE INALAMBRICO) ENSAMBLADO Y CONFIGURADO', 'category' => 'CPU Ensamblados'],
            ['cod' => '9927', 'name' => 'CPU DESKTOP COMPUTADOR DE ESCRITORIO GAMER INFINYTEK H-15PRO WHITE INTEL CORE I5 12400 (12VA) ASUS H610MK RAM 16GB SSD512GB M.2 VIDEO MSI GTX 1650 4GB WIFI TL-WN823N (INCLUYE TECLADO & MOUSE INALAMBRICO) ENSAMBLADO Y CONFIGURADO', 'category' => 'CPU Ensamblados'],
            ['cod' => '9924', 'name' => 'CPU DESKTOP COMPUTADOR DE ESCRITORIO GAMER INFINYTEK H-15PRO BLACK INTEL CORE I5 12400 (12VA) ASUS H610MK RAM 16GB SSD512GB M.2 VIDEO MSI GTX 1650 4GB WIFI TL-WN823N (INCLUYE TECLADO & MOUSE INALAMBRICO) ENSAMBLADO Y CONFIGURADO', 'category' => 'CPU Ensamblados'],
            ['cod' => '8669', 'name' => 'CPU DESKTOP COMPUTADOR DE ESCRITORIO INFINYTEK GAMER H-15 BLACK INTEL CORE I7 14700 (14VA) GIGABYTE B760M K RAM 16GB SSD 1TB FUENTE CERTIFICADA 650W (INCLUYE TECLADO - MOUSE) ENSAMBLADO Y CONFIGURADO', 'category' => 'CPU Ensamblados'],
            ['cod' => '8596', 'name' => 'CPU DESKTOP COMPUTADOR DE ESCRITORIO HP VICTUS 15L TG02-0130 RYZEN 7 5700G (5TH) RAM 16GB SSD 512GB AMD RX 6600XT 8GB WINDOWS 11 HOME BLACK', 'category' => 'CPU Ensamblados'],
            ['cod' => '9218', 'name' => 'PC ESCRITORIO WORK STATION ASUS ROG G16 INTEL CORE I7-14700F (14VA) RAM 32GB SSD 1TB NVIDIA GEFORCE RTX 4060 8GB WINDOWS 11 HOME WIFI TECLADO + MOUSE USB', 'category' => 'CPU Ensamblados'],

            // DISCOS DUROS PC
            ['cod' => '586', 'name' => 'DISCO DURO 2TB SATA WESTERN DIGITAL PURPLE WD23PURZ 5400RPM 3.5 256MB', 'category' => 'Discos Duros'],
            ['cod' => '2918', 'name' => 'DISCO DURO 4TB SATA SEAGATE BARRACUDA 5400RPM - ST4000DM004', 'category' => 'Discos Duros'],
            ['cod' => '7210', 'name' => 'DISCO DURO 4TB SATA WESTERN DIGITAL PURPLE WD43PURZ 5400RPM 3.5 6GB/S', 'category' => 'Discos Duros'],
            ['cod' => '9254', 'name' => 'DISCO DURO 6TB SATA WESTERN DIGITAL WD64PURZ PURPLE SATA 3.5', 'category' => 'Discos Duros'],
            ['cod' => '9511', 'name' => 'DISCO DURO 8TB SATA WESTERN DIGITAL WD85PURZ PURPLE SATA 3.5', 'category' => 'Discos Duros'],
            ['cod' => '3708', 'name' => 'DISCO DURO EXTERNO 2TB SATA ADATA 2.5 HD330 USB 3.0 ANTIGOLPES RED', 'category' => 'Discos Duros'],
            ['cod' => '3709', 'name' => 'DISCO DURO EXTERNO 2TB SATA ADATA 2.5 HD330 USB 3.2 ANTIGOLPES BLACK', 'category' => 'Discos Duros'],
            ['cod' => '9822', 'name' => 'DISCO DURO EXTERNO 2TB SATA TOSHIBA 2.5 USB 3.0 BLACK - HDTCA20XK3AA', 'category' => 'Discos Duros'],
            ['cod' => '3107', 'name' => 'DISCO DURO EXTERNO 4TB SATA ADATA 2.5 HD650 USB 3.2', 'category' => 'Discos Duros'],
            ['cod' => '7478', 'name' => 'DISCO DURO EXTERNO 4TB ADVANCE SATA TOSHIBA 2.5 USB 3.0 BLACK CON SOFTWARE COPIA DE SEGURIDAD', 'category' => 'Discos Duros'],
            ['cod' => '9828', 'name' => 'DISCO DURO EXTERNO 6TB ADATA HM800 EXTERNO 3.5 HDD TV SUPPORT', 'category' => 'Discos Duros'],

            // DISCOS SOLIDOS EXTERNO
            ['cod' => '7503', 'name' => 'DISCO SOLIDO EXTERNO 512GB HYUNDAI ULTRA PORTABLE PC/MAC/MOBILE USB-C TO C / USB-A TO C DUAL CABLE INCLUDED UP TO 450MB/S GEN USB 3.1 BLACK', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '7522', 'name' => 'DISCO SOLIDO EXTERNO 512GB HYUNDAI ULTRA PORTABLE PC/MAC/MOBILE USB-C TO C / USB-A TO C DUAL CABLE INCLUDED UP TO 450MB/S GEN USB 3.1 RED', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '7743', 'name' => 'DISCO SOLIDO EXTERNO 512GB LEXAR SL200 TIPO C USB 3.1 - LSL200X512G-RNNNU', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8662', 'name' => 'DISCO SOLIDO EXTERNO 1TB (1000GB) SSD ADATA SD810 USB-C SILVER 2000MB/S IP68 WATER RESISTANCE', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8014', 'name' => 'DISCO SOLIDO EXTERNO 1TB (1000GB) SSD Kingston BLACK - SXS1000/1000G', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '7807', 'name' => 'DISCO SOLIDO EXTERNO 1TB (1000GB) SSD Kingston RED - SXS1000/1000G', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8850', 'name' => 'DISCO SOLIDO EXTERNO 2TB (2000GB) SSD Kingston BLACK- SXS1000/2000G', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '9542', 'name' => 'DISCO SOLIDO EXTERNO 2TB (2000GB) SSD Kingston RED - SXS1000R/2000G', 'category' => 'Discos Sólidos SSD'],
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

        $this->command->info("✅ Primera mitad - Productos creados: {$created}");
    }
}
