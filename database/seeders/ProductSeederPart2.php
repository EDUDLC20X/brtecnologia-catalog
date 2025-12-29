<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeederPart2 extends Seeder
{
    /**
     * Segunda mitad de productos reales de B&R Tecnología
     */
    public function run(): void
    {
        // Obtener IDs de categorías existentes
        $categoryIds = Category::pluck('id', 'name')->toArray();

        $products = [
            // DISCOS SOLIDOS INTERNOS
            ['cod' => '5568', 'name' => 'DISCO SOLIDO 256GB ADATA LEGEND 710 PCIE GEN3 X4 M.2 PCIe NVMe 1.3 2280 - ALEG-710-256GCS', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '7502', 'name' => 'DISCO SOLIDO 256GB HYUNDAI SSD 2.5 SATA III 10X FASTER 3D TLC (5 AÑOS DE GARANTIA)', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '1289', 'name' => 'DISCO SOLIDO 240GB KINGSTON SA400S37 2.5 SATA 10X FASTER - SA400S37/240G', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '10150', 'name' => 'DISCO SOLIDO 480GB HIKSEMI WAVE SATA 2.5 3.0', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '5849', 'name' => 'DISCO SOLIDO 240GB WESTERN DIGITAL SATA 2.5 GREEN WDS240G3G0A', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '1217', 'name' => 'DISCO SOLIDO 480GB KINGSTON SA400S37 2.5 SATA 10X FASTER - SA400S37/480G', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '10115', 'name' => 'DISCO SOLIDO 512GB LEXAR LNS100 2.5 SATA III 6GB/s - LNS100512RB', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8018', 'name' => 'DISCO SOLIDO 512GB HYUNDAI SSD 2.5 SATA III 10X FASTER 3D TLC (5 AÑOS DE GARANTIA)', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8086', 'name' => 'DISCO SOLIDO 512GB MICRON MTFDKCD512TFK PCIE GEN4X4 M.2 PCIe NVMe 2450', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '5190', 'name' => 'DISCO SOLIDO 512GB ADATA ASU650SS-512GT-R 2.5', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '7495', 'name' => 'DISCO SOLIDO 512GB ADATA LEGEND 710 PCIE GEN3 X4 M.2 PCIe NVMe 1.3 2280 - ALEG-710-512GCS', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8041', 'name' => 'DISCO SOLIDO 500GB KINGSTON FURY RENEGADE SFYRS-500G M.2 2280 PCIe 4.0 NVMe- SFYRS500GB', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '10145', 'name' => 'DISCO SOLIDO 2TB ADATA LEGEND 900 M.2 PCIE GEN4X4 MVME 7000MB-S COMP 3Y- SLEG-900-2TCS', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '1716', 'name' => 'DISCO SOLIDO 960GB KINGSTON SA400S37 2.5 SATA 10X FASTER - SA400S37/960G', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '9847', 'name' => 'DISCO SOLIDO 1TB KINGSTON SNV3S-1000G NV3 M.2 2280 PCIe 4.0 NVMe 6000MB - SNV3S/1000GB', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '5655', 'name' => 'DISCO SOLIDO 1TB KINGSTON SNVS-1000G NV2 M.2 2280 PCIe 4.0 NVMe 3500MB - SNV2S/1000GB', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '10221', 'name' => 'DISCO SOLIDO 1000GB (1TB) HYUNDAI SSD 2.5 SATA III 10X FASTER 3D TLC (5 AÑOS DE GARANTIA)', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8487', 'name' => 'DISCO SOLIDO 1TB ADATA LEGEND 710 PCIE GEN3X4 M.2 PCIe NVMe 2280 - ALEG-710-1TCS', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8575', 'name' => 'DISCO SOLIDO 1TB DATO SD700 SATA 2.5', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '8427', 'name' => 'DISCO SOLIDO 1TB TEAM GROUP 2.5 SATA - T253X6001T0C101', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '10279', 'name' => 'DISCO SOLIDO 1TB ADATA LEGEND 860 PCIE GEN4X4 M.2 PCIE NVME 2280-SLEG-860-1T-CB', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '7534', 'name' => 'DISCO SOLIDO 2TB ADATA LEGEND 710 PCIE GEN3 X4 M.2 PCIe NVMe 1.3 2280 - ALEG-710-2TCS', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '10174', 'name' => 'DISCO SOLIDO 2TB WESTERN DIGITAL SN350 WDS200T2G0A SATA III 2.5 7MM GREEN', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '9936', 'name' => 'DISCO SOLIDO 2TB WESTERN DIGITAL SN350 WDS200T3G0C NVME M.2 2280 GREEN', 'category' => 'Discos Sólidos SSD'],
            ['cod' => '7512', 'name' => 'DISCO SOLIDO 2TB KINGSTON SNV2S-2000G NV2 M.2 2280 PCIe 4.0 NVMe 3500MB - SNV2S/2000GB', 'category' => 'Discos Sólidos SSD'],

            // DVD WRITER
            ['cod' => '9457', 'name' => 'DVD WRITER EXTERNO ALTEK USB 3.0 BLACK', 'category' => 'DVD Writer'],

            // ESTUCHES / MALETAS / MOCHILAS
            ['cod' => '8057', 'name' => 'ESTUCHE PARA TABLET LENOVO M10HD 2ND NEGRO MODO STAND/PROTECCION SOLIDA/PELICULA PROTECTORA INCLUIDA', 'category' => 'Estuches y Mochilas'],
            ['cod' => '104', 'name' => 'ESTUCHE PARA LAPTOP VIEWSTAR (NEOPRENO) 14', 'category' => 'Estuches y Mochilas'],
            ['cod' => '6007', 'name' => 'ESTUCHE PARA LAPTOP MALETIN 15.6 INFINYTEK CON BOLSILLO - GRIS NEGRO', 'category' => 'Estuches y Mochilas'],
            ['cod' => '8622', 'name' => 'ESTUCHE PARA LAPTOP MALETIN 15.6 INFINYTEK CON BOLSILLO - AZUL', 'category' => 'Estuches y Mochilas'],
            ['cod' => '8624', 'name' => 'ESTUCHE PARA LAPTOP MALETIN 15.6 INFINYTEK CON BOLSILLO - GRIS NARANJA', 'category' => 'Estuches y Mochilas'],
            ['cod' => '8623', 'name' => 'ESTUCHE PARA LAPTOP MALETIN 15.6 INFINYTEK CON BOLSILLO - NEGRO', 'category' => 'Estuches y Mochilas'],
            ['cod' => '8625', 'name' => 'ESTUCHE PARA LAPTOP MALETIN 15.6 INFINYTEK CON BOLSILLO - ROSADO', 'category' => 'Estuches y Mochilas'],
            ['cod' => '8626', 'name' => 'ESTUCHE PARA LAPTOP (ANTI-GOLPES) 15.6 INFINYTEK', 'category' => 'Estuches y Mochilas'],
            ['cod' => '6042', 'name' => 'MALETA PLASTICA DE MANO TIPO NYLON 41X33X8', 'category' => 'Estuches y Mochilas'],
            ['cod' => '9608', 'name' => 'MOCHILA INFINYTEK PEGASUS ROSADA - 4531-3', 'category' => 'Estuches y Mochilas'],
            ['cod' => '9609', 'name' => 'MOCHILA INFINYTEK PEGASUS GRIS 14 a 15.5 - 4531-4', 'category' => 'Estuches y Mochilas'],
            ['cod' => '9606', 'name' => 'MOCHILA INFINYTEK PEGASUS NEGRA 14 a 15.5 - 4531-1', 'category' => 'Estuches y Mochilas'],
            ['cod' => '9607', 'name' => 'MOCHILA INFINYTEK PEGASUS AZUL 14 a 15.5 - 4531-2', 'category' => 'Estuches y Mochilas'],
            ['cod' => '7815', 'name' => 'MOCHILA PARA LAPTOP LENOVO 15.6 B210 GRIS', 'category' => 'Estuches y Mochilas'],
            ['cod' => '8343', 'name' => 'MOCHILA PARA LAPTOP DYNABOOK 15.6 PS0012UA - NYLON GRIS', 'category' => 'Estuches y Mochilas'],

            // ELECTRODOMESTICOS
            ['cod' => '8813', 'name' => 'FREIDORA DE AIRE MARVO HAF-100 4.5L', 'category' => 'Electrodomésticos'],
            ['cod' => '9277', 'name' => 'REFRIGERADORA RCA BCD-606WD 570 LTRS CON DISPENSADOR DE AGUA CONTROL DE TEMPERATURA ELECTRONICO SILVER', 'category' => 'Electrodomésticos'],
            ['cod' => '10193', 'name' => 'REFRIGERADORA RCA 157L MRF-159 160 LITROS BOTTOM FREEZER COLOR SILVER', 'category' => 'Electrodomésticos'],
            ['cod' => '10162', 'name' => 'REFRIGERADORA WHIRLPOOL WRW25CKTWW 279 LITROS TOP MOUNT DISPENSADOR ACERO GALVANIZADO', 'category' => 'Electrodomésticos'],
            ['cod' => '9279', 'name' => 'REFRIGERADORA TCL P650SBG 596 LTRS CONTROL ELECTRONICO LUZ LED ESTANTES DE VIDRIO CAJONES FRESCOS FUNCION VACACIONES FUNCION DE ALARMA INVERSOR ECOLOGICO PLATA SILVER', 'category' => 'Electrodomésticos'],
            ['cod' => '10190', 'name' => 'LAVADORA LG WT19WVTM 19KG CARGA SUPERIOR INVERTER TURBO DRUM BLANCA', 'category' => 'Electrodomésticos'],
            ['cod' => '5124', 'name' => 'LAVADORA RCA 15KG XQB150-A568D', 'category' => 'Electrodomésticos'],

            // EXTENSOR DE WIFI
            ['cod' => '9541', 'name' => 'EXTENSOR REPETIDOR HIKVISION DS-3WRE12C AC1200 / WIRELESS WIFI 5 / 2 ANTENAS DE ALTA GANANCIA DUAL BAND', 'category' => 'Extensores WiFi'],

            // FLASH MEMORY
            ['cod' => '6296', 'name' => 'FLASH MEMORY 64GB KINGSTON DATATRAVEL EXODIA ONYX 3.2 BLACK (DTXON/64GB)', 'category' => 'Flash Memory'],
            ['cod' => '7511', 'name' => 'FLASH MEMORY 64GB KINGSTON KC-U2L64-7LB EXODIA USB 3.2 NEON AQUA BLUE', 'category' => 'Flash Memory'],
            ['cod' => '7463', 'name' => 'FLASH MEMORY 64GB KINGSTON KC-U2L64-7LG EXODIA USB 3.2 NEON GREEN', 'category' => 'Flash Memory'],
            ['cod' => '7464', 'name' => 'FLASH MEMORY 64GB KINGSTON KC-U2L64-7LN EXODIA USB 3.2 NEON PINK', 'category' => 'Flash Memory'],
            ['cod' => '7462', 'name' => 'FLASH MEMORY 64GB KINGSTON KC-U2L64-7LP EXODIA USB 3.2 NEON PURPLE', 'category' => 'Flash Memory'],
            ['cod' => '8224', 'name' => 'FLASH MEMORY 128GB KINGSTON DATATRAVEL EXODIA 3.2 BLACK + YELLOW (DTX/128GB)', 'category' => 'Flash Memory'],
            ['cod' => '6297', 'name' => 'FLASH MEMORY 128GB KINGSTON DATATRAVEL EXODIA ONYX 3.2 BLACK (DTXON/128GB)', 'category' => 'Flash Memory'],
            ['cod' => '8221', 'name' => 'FLASH MEMORY 256GB KINGSTON DATATRAVEL EXODIA 3.2 BLACK + PINK (DTX/256GB)', 'category' => 'Flash Memory'],
            ['cod' => '7801', 'name' => 'FLASH MEMORY 256GB KINGSTON DATATRAVEL EXODIA 3.2 BLACK + TURQUEZA (DTXM/256GB)', 'category' => 'Flash Memory'],

            // FOCO / BOMBILLO
            ['cod' => '4281', 'name' => 'FOCO BOMBILLO RCA LED 6500K LUZ BLANCA 9W SUPER PACK 3 PZS 4R98007', 'category' => 'Herramientas y Limpieza'],

            // FUENTE DE PODER
            ['cod' => '5094', 'name' => 'FUENTE DE PODER INFINYTEK ATX 850W (20-24 PINES) (IDEx2) (SATAx3) BLACK 8.5', 'category' => 'Fuentes de Poder'],
            ['cod' => '9596', 'name' => 'FUENTE DE PODER ALTEK ATX 875W (20-24 PINES) (IDEx2) (SATAx3) BLACK 8.5', 'category' => 'Fuentes de Poder'],
            ['cod' => '8793', 'name' => 'FUENTE DE PODER MINI ALTEK ITX 875W 16-24 PINES BLACK', 'category' => 'Fuentes de Poder'],
            ['cod' => '9552', 'name' => 'FUENTE DE PODER CERTIFICADA INFINYTEK 550W 80 PLUS GAMER 20+4PIN 6x SATA 2xP8 (P4+P4) 2x P8(6+2) 2xIDE', 'category' => 'Fuentes de Poder'],
            ['cod' => '4316', 'name' => 'FUENTE DE PODER CERTIFICADA INFINYTEK 650W BRONZE 80 PLUS LED RGB GAMER 20+4 PIN 6xSATA 2xP8 (P4+P4) 2xP8 (6+2) 2xIDE1 FAN', 'category' => 'Fuentes de Poder'],
            ['cod' => '7294', 'name' => 'FUENTE DE PODER CERTIFICADA INFINYTEK 850 WTS 80 PLUS BRONZE 20+4 PIN 6xSATA 2xP8 (P4+P4) 2xP8 (6+2) 2xIDE1 FAN', 'category' => 'Fuentes de Poder'],
            ['cod' => '8627', 'name' => 'FUENTE DE PODER CERTIFICADA INFINYTEK 1000 WTS (1KW) 80 PLUS BRONZE M/B 20+4PIN IDE+3SATA PCI-E P(6+2)+P (6+2) PIN CPU P8 (4+4)+P (8+4) PIN', 'category' => 'Fuentes de Poder'],

            // HUB
            ['cod' => '7938', 'name' => 'HUB ADAPTADOR TIPO C A 4 PUERTOS USB ANERA 1X USB 3.0 USB 3 X USB 2.0 AE-CHUB3123', 'category' => 'Hubs USB'],
            ['cod' => '8999', 'name' => 'HUB GENIUS UH545 USB C A 5 PUERTOS 3 USB A 1 USB C 1 RJ45 GE WIN 10 11 MAC', 'category' => 'Hubs USB'],
            ['cod' => '5425', 'name' => 'HUB ADAPTADOR DOCKING STATION 11 EN 1 BYL-2003 PUERTO USB-C A 3 USB 1USB 3.0 SD TF LAN AUDIO HDMI VGA PD', 'category' => 'Hubs USB'],
            ['cod' => '8631', 'name' => 'HUB INFINYTEK 5 IN 1 TIPO C HUB USB3.0 TO USB 3.0 x1+2.0 x2+SD/TF - YG-21037T', 'category' => 'Hubs USB'],
            ['cod' => '8630', 'name' => 'HUB INFINYTEK 6 IN 1 TYPE-C TO HDMI+USB 3.0 x1+USB2.0 x1+SD+TF - BYL-2010N3', 'category' => 'Hubs USB'],
            ['cod' => '8628', 'name' => 'HUB INFINYTEK 10IN 1 TYPE-C TO HDMI+USB 3.0 x3+RJ45+PD+AV+SD+TF+AUDIO - BYL-2212', 'category' => 'Hubs USB'],

            // CAJA DE MANTENIMIENTO IMPRESORAS
            ['cod' => '9490', 'name' => 'CAJA DE MANTENIMIENTO EPSON PX4MB10/C9382 SERIES WF-C5000 WF-M5000 WF-C5890 C12C938211', 'category' => 'Cartuchos y Cabezales'],
            ['cod' => '6285', 'name' => 'CAJA DE MANTENIMIENTO EPSON T671200 PARA WORKFORCE PRO WF-6090 WF-6590 WF-8090 WF8590', 'category' => 'Cartuchos y Cabezales'],
            ['cod' => '6156', 'name' => 'CAJA DE MANTENIMIENTO EPSON T671600 PARA WORKFORCE PRO ET-8700 WF-C529R WF-C5790 WFC579R WF-M5298DW WF-M5299 WF-M5799', 'category' => 'Cartuchos y Cabezales'],

            // IMPRESORAS
            ['cod' => '4472', 'name' => 'IMPRESORA BROTHER DCP-T720DW MULTIFUNCION WIFI / DUPLEX / ADF (REGISTRO PARA 2 AÑOS DE GARANTIA) GRATIS RESMA DE PAPEL', 'category' => 'Impresoras'],
            ['cod' => '1308', 'name' => 'IMPRESORA CANON G3110 MULTIFUNCION (IMPRESORA-COPIADORA-SANER) WIFI (REGISTRO PARA 3 AÑOS DE GARANTIA)', 'category' => 'Impresoras'],
            ['cod' => '8074', 'name' => 'IMPRESORA CANON G3170 MULTIFUNCION WIFI (REGISTRO PARA 3 AÑOS DE GARANTIA)', 'category' => 'Impresoras'],
            ['cod' => '5333', 'name' => 'IMPRESORA CANON GX7010 MFP MAXIFY 4471C004AA DUPLEX/ADF/WIFI/PAN 2.7 PUL/2 BAND. 250H/GI16', 'category' => 'Impresoras'],
            ['cod' => '6148', 'name' => 'IMPRESORA EPSON L1250 WIFI COLOR CON SISTEMA DE TINTA CONTINUO FABRICA CON LA APP EPSON SMART PANEL COPIA Y ESCANEA', 'category' => 'Impresoras'],
            ['cod' => '4524', 'name' => 'IMPRESORA EPSON L3250 MULTIFUNCION WIFI CON SISTEMA DE TINTA CONTINUA ORIGINAL', 'category' => 'Impresoras'],
            ['cod' => '5361', 'name' => 'IMPRESORA EPSON L4260 MULTIFUNCION SISTEMA DE TINTA CONTINUO ORIGINAL ECOTANK WIFI - DUPLEX IMPRESION DOBLE CARA', 'category' => 'Impresoras'],
            ['cod' => '6992', 'name' => 'IMPRESORA EPSON L5590 MULTIFUNCION CON SISTEMA DE TINTA CONTINUO ORIGINAL WI-FI y ETHERNET PANTALLA ADF de 30 Hojas', 'category' => 'Impresoras'],
            ['cod' => '4402', 'name' => 'IMPRESORA EPSON L6490 MULTIFUNCION MULTIFUNCION WIFI COPIA ESCANEA FAX ETHERNET 110V', 'category' => 'Impresoras'],
            ['cod' => '7836', 'name' => 'IMPRESORA EPSON L8050 FOTOGRAFICA WIFI DIRECT A4 CD DVD IDF PVC USB BLACK', 'category' => 'Impresoras'],
            ['cod' => '7304', 'name' => 'IMPRESORA EPSON L15150 MULTIFUNCION A3 SISTEMA DE TINTA CONTINUO ORIGINAL WIFI ADF DUPLEX', 'category' => 'Impresoras'],
            ['cod' => '7159', 'name' => 'IMPRESORA HP 580 SMART TANK WI-FI MULTIFUNCION (SISTEMA DE TINTA CONTINUA) COMPATIBLE WINDOWS & MACOS (1 BOTELLA GRATIS) (2 AÑOS GARANTÍA)', 'category' => 'Impresoras'],
            ['cod' => '7112', 'name' => 'IMPRESORA HP 720 MULTIFUNCION CON SISTEMA DE TINTA CONTINUA WIFI BLUETOOTH DUPLEX', 'category' => 'Impresoras'],
            ['cod' => '10360', 'name' => 'IMPRESORA SAT Q22UE TERMICA USB ETHERNET 10364 (POS-IMPRESION TICKETS) 230MM/S PAPEL 80MM 1D Y 2D TERMICA DIRECTA', 'category' => 'Impresoras'],
            ['cod' => '7348', 'name' => 'IMPRESORA 3NSTAR PPT205BT TERMICA PORTATIL BLUETOOTH 4.0 USB', 'category' => 'Impresoras'],
            ['cod' => '4802', 'name' => 'IMPRESORA 3NSTAR PPT305BT TERMICA PORTATIL BLUETOOTH 4.0 USB', 'category' => 'Impresoras'],
            ['cod' => '8922', 'name' => 'IMPRESORA 3NSTAR RPT004 TERMICA USB ETHERNET LAN 80mm CORTADOR VELOCIDAD IMPRESION 230MM/S', 'category' => 'Impresoras'],
            ['cod' => '7490', 'name' => 'IMPRESORA 3NSTAR RPT008 TERMICA USB SERIAL ETHERNET LAN 79.50mm VELOCIDAD IMPRESION 260MM/S CORTADOR', 'category' => 'Impresoras'],
            ['cod' => '2159', 'name' => 'IMPRESORA EPSON TM-U220D MATRICIAL BICROMATICA ETHERNET USB CORTADORA MANUAL', 'category' => 'Impresoras'],
            ['cod' => '5221', 'name' => 'IMPRESORA EPSON F170 DE SUBLIMACION DE TINTA INCLUYE TINTA', 'category' => 'Impresoras'],
            ['cod' => '1930', 'name' => 'IMPRESORA EPSON WORKFORCE PRO WF6590DWF-MFP MULTIFUNCION WIRELESS ETHERNET &PCL/PS FAX DUPLEX ADF', 'category' => 'Impresoras'],

            // CAJA DE DINERO / CONTADOR DE BILLETES
            ['cod' => '10361', 'name' => 'CAJON DE DINERO SAT 119-NEGRO RJ11 USB RS232 5 COMPARTIMIENTOS PARA MONEDAS 4 PARA BILLETES INCLUYE LLAVES', 'category' => 'Impresoras'],
            ['cod' => '4477', 'name' => 'CONTADORA DE BILLETES 3NSTAR BC1005 CUENTA 1000 BILLETES SOPORTA LOTES HASTA 300 BILLETES TRES PANTALLAS LCD', 'category' => 'Impresoras'],

            // LECTOR CODIGO DE BARRA / RELOJ BIOMETRICO
            ['cod' => '9878', 'name' => 'LECTOR CODIGO DE BARRAS IMAGER SAT AI8604 ID USB 1D 2D DE MESA OMNIDIRECCIONAL', 'category' => 'Lectores de Código'],
            ['cod' => '10007', 'name' => 'LECTOR CODIGO DE BARRA 3NSTAR SC402 USB CON BASE 2D', 'category' => 'Lectores de Código'],
            ['cod' => '1669', 'name' => 'ESCANER - SCANNER LECTOR CODIGO DE BARRA SAT LD101R LASER USB', 'category' => 'Lectores de Código'],
            ['cod' => '9903', 'name' => 'LECTOR CODIGO DE BARRAS 3NSTAR SC504 OMNIDIRECCIONAL 2D 640X480 PIXEL', 'category' => 'Lectores de Código'],
            ['cod' => '9902', 'name' => 'LECTOR CODIGO DE BARRA 3NSTAR SC430 INHALAMBRICO 2.4GHZ 2D CON BASE', 'category' => 'Lectores de Código'],
            ['cod' => '8780', 'name' => 'LECTOR CODIGO DE BARRAS 3NSTAR SC550 IMAGER 2D SOBREMESA OMNIDIRECCIONAL LECTURA AUTOMATICA/USB', 'category' => 'Lectores de Código'],

            // LICENCIAS SOFTWARE
            ['cod' => '7288', 'name' => 'LICENCIA KASPERSKY STANDARD LATAM (1 Dispositivo) (1 Cuenta) (1 AÑo) FISICA BLISTER', 'category' => 'Licencias Software'],
            ['cod' => '9867', 'name' => 'LICENCIA KASPERSKY STANDARD LATAM (1 Dispositivo) (1 Cuenta) (1 AÑo) ELECTRONICAS BUNDLE', 'category' => 'Licencias Software'],
            ['cod' => '7891', 'name' => 'LICENCIA ANTIVIRUS KASPERSKY PREMIUM LATAM (1 DISPOSITIVO) (1 CUENTA) (1 AÑO) (1 KPM) (1 AÑO SAFE KIDS) FISICA BLISTER', 'category' => 'Licencias Software'],
            ['cod' => '7455', 'name' => 'LICENCIA ANTIVIRUS KASPERSKY PLUS LATAM (1 DISPOSITIVO) (1 CUENTA) (1 KPM) (1 AÑO) FISICA BLISTER', 'category' => 'Licencias Software'],
            ['cod' => '9980', 'name' => 'LICENCIA ANTIVIRUS KASPERSKY ESSENTIALS PROFESIONAL FOR PC (1 AÑO) (PRODUCTO BASE)', 'category' => 'Licencias Software'],
            ['cod' => '5760', 'name' => 'LICENCIA ANTIVIRUS KASPERSKY SMALL OFFICE SECURITY 8 (5 DISPOSITIVOS PC) (5 DISPOSITIVOS CELULARES) (1 SERVIDOR) (5 VPN) (5 KPM) (1 AÑO)', 'category' => 'Licencias Software'],
            ['cod' => '7966', 'name' => 'LICENCIA MICROSOFT OFFICE 2021 FOR WINDOWS OEM (LIFE-TIME PC) ORIGINAL VIRTUAL (WORD - EXCEL - POWERPOINT - OUTLOOK - PUBLISHER - ACCESS)', 'category' => 'Licencias Software'],
            ['cod' => '5761', 'name' => 'LICENCIA MICROSOFT WINDOWS 11 PRO ESPAÑOL STICKER PERPETUA', 'category' => 'Licencias Software'],
            ['cod' => '7839', 'name' => 'LICENCIA MICROSOFT WINDOWS 11 PRO OEM 64 BITS (LIFE-TIME PC) ORIGINAL VIRTUAL PERPETUA', 'category' => 'Licencias Software'],
            ['cod' => '2288', 'name' => 'LICENCIA MICROSOFT OFFICE 365 BUSINESS STANDAR ESD SUSCRIPCION 1 AÑO', 'category' => 'Licencias Software'],

            // MAINBOARD
            ['cod' => '9770', 'name' => 'MAINBOARD ASUS PRIME A620M-K RYZEN AM5 DDR5 MICRO-ATX', 'category' => 'Mainboards'],
            ['cod' => '10250', 'name' => 'MAINBOARD PC MSI A520M-A PRO AM4 PCI EXPRESS 3.0 NVME 3RA GEN', 'category' => 'Mainboards'],
            ['cod' => '7224', 'name' => 'MAINBOARD PC ASUS PRIME H510M-K R2.0 (10MA - 11VA) PCIe 4.0 2xDDR4 2xM.2 HDMI USB 3.2 DSUB 1GB LAN LGA1200 mATX', 'category' => 'Mainboards'],
            ['cod' => '9592', 'name' => 'MAINBOARD PC MSI PRO H610M-S DDR4 LGA1700 12VA 13VA M.2 2 DIMMS 2X SATA 4X USB 3.0', 'category' => 'Mainboards'],
            ['cod' => '5686', 'name' => 'MAINBOARD PC ASUS PRIME H610MK-D4 DDR4 LGA1700 12VA (ACTUALIZABLE BIOS PARA 13VA) M.2 2 DDR4 PCIE X16 SATA HDMI/D-SUB USB 3.2 GEN 1 RGB HEADER FAN XPERT EPU UEFI BIOS 5X PROTECTION III', 'category' => 'Mainboards'],
            ['cod' => '8659', 'name' => 'MAINBOARD GIGABYTE B760M K 14va LGA1700 4DDR4 D-Sub HDMI 2M.2 PCIe4.0 mATX', 'category' => 'Mainboards'],
            ['cod' => '8725', 'name' => 'MAINBOARD MSI B760P 14VA LGA1700 2DDR4 HDMI DP 2M.2 3USB3.2 PCIE4.0 MATX', 'category' => 'Mainboards'],
            ['cod' => '8116', 'name' => 'MAINBOARD ASROCK Z790 STEEL LEGEND WIFI BT 13va LGA-1700 4DDR5 HDMI WIFI BT 4M.2 9USB3.2 PCIe5.0', 'category' => 'Mainboards'],

            // MICRO SD
            ['cod' => '1194', 'name' => 'MEMORIA MICRO SD 128GB KINGSTON CLAS10', 'category' => 'Memorias MicroSD'],
            ['cod' => '1723', 'name' => 'MEMORIA MICRO SD 256GB KINGSTON CLAS10 CANVAS CON ADAPTADOR', 'category' => 'Memorias MicroSD'],
            ['cod' => '5962', 'name' => 'MEMORIA MICRO SD SANDISK 128GB EXTREME UHS-I microSDXC - SDSQX44-128G-AN6MA', 'category' => 'Memorias MicroSD'],
            ['cod' => '8059', 'name' => 'MEMORIA MICRO SD 256GB WESTER DIGITAL PURPLE QD101 SIN ADAPTADOR - ULTRA RESISTENCIA', 'category' => 'Memorias MicroSD'],

            // MEMORIA RAM DDR3/DDR4/DDR5 PARA PC
            ['cod' => '9720', 'name' => 'MEMORIA RAM DDR3 DIMM 8GB PC1600 DATO DT8GB3DU16', 'category' => 'Memorias RAM'],
            ['cod' => '1857', 'name' => 'MEMORIA RAM DDR3 DIMM 8GB PC1600 GOLDEN GM16N11/8', 'category' => 'Memorias RAM'],
            ['cod' => '10182', 'name' => 'MEMORIA RAM DDR3L DIMM 8GB PC 1600 ADATA LOW VOLTAJE', 'category' => 'Memorias RAM'],
            ['cod' => '6241', 'name' => 'MEMORIA RAM DDR4 DIMM 8GB PC3200 SAMSUNG', 'category' => 'Memorias RAM'],
            ['cod' => '5480', 'name' => 'MEMORIA RAM DDR4 DIMM 8GB PC3200 ADATA PC4-25600', 'category' => 'Memorias RAM'],
            ['cod' => '5666', 'name' => 'MEMORIA RAM DDR4 DIMM 16GB PC3200 ADATA PC4-25600', 'category' => 'Memorias RAM'],
            ['cod' => '8547', 'name' => 'MEMORIA RAM DDR4 DIMM 8GB PC3200 MUSHKIN PC', 'category' => 'Memorias RAM'],
            ['cod' => '4652', 'name' => 'MEMORIA RAM DDR4 DIMM 8GB PC3200 KINGSTON KVR32N22S8L/8 64BIT', 'category' => 'Memorias RAM'],
            ['cod' => '8613', 'name' => 'MEMORIA RAM DDR4 DIMM 8GB PC3200 HIKSEMI', 'category' => 'Memorias RAM'],
            ['cod' => '4237', 'name' => 'MEMORIA RAM DDR4 DIMM 8GB PC3200 KINGSTON KVR32N22S6/8', 'category' => 'Memorias RAM'],
            ['cod' => '7500', 'name' => 'MEMORIA RAM DDR4 DIMM 8GB PC 3200 KINGSTON FURY BEAST RGB- KF432C16BB2A/8', 'category' => 'Memorias RAM'],
            ['cod' => '6067', 'name' => 'MEMORIA RAM DDR4 DIMM 16GB PC 2933Y HP P00920-B21 PC4 21300', 'category' => 'Memorias RAM'],
            ['cod' => '7133', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 32GB PC3200 ADATA LAPTOP PC4-25600', 'category' => 'Memorias RAM'],
            ['cod' => '7978', 'name' => 'MEMORIA RAM DDR5 DIMM 16GB PC 4800 ADATA PC5-38400', 'category' => 'Memorias RAM'],
            ['cod' => '9313', 'name' => 'MEMORIA RAM DDR5 DIMM 16GB PC 5200 CORSAIR C40 BLACK', 'category' => 'Memorias RAM'],
            ['cod' => '8001', 'name' => 'MEMORIA RAM DDR5 DIMM 8GB PC 4800 ADATA PREMIER', 'category' => 'Memorias RAM'],
            ['cod' => '10114', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 16GB PC5600 LEXAR LAPTOP 5600MT/S', 'category' => 'Memorias RAM'],
            ['cod' => '10227', 'name' => 'MEMORIA RAM DDR5 DIMM 16GB PC6000 ADATA WHITE', 'category' => 'Memorias RAM'],
            ['cod' => '3088', 'name' => 'MEMORIA RAM DDR3L SO-DIMM 8GB PC1600 GOLDEN LAPTOP GM16LS11/8', 'category' => 'Memorias RAM'],
            ['cod' => '6196', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 16GB PC 3200 KINGSTON FURY LAPTOP KF432S20IB/16', 'category' => 'Memorias RAM'],
            ['cod' => '3756', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 8GB PC3200 KINGSTON LAPTOP KVR32S22S8/8', 'category' => 'Memorias RAM'],
            ['cod' => '10266', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 16GB PC3200 MUSHKIN LAPTOP', 'category' => 'Memorias RAM'],
            ['cod' => '4143', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 4GB PC3200 SAMSUNG LAPTOP M471A5244CB0-CWE', 'category' => 'Memorias RAM'],
            ['cod' => '4658', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 8GB PC3200 MUSHKIN LAPTOP', 'category' => 'Memorias RAM'],
            ['cod' => '6298', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 8GB PC3200 KINGSTON LAPTOP KVR32S22S6/8', 'category' => 'Memorias RAM'],
            ['cod' => '4640', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 8GB PC3200 ADATA LAPTOP PC4-25600', 'category' => 'Memorias RAM'],
            ['cod' => '4641', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 16GB PC3200 ADATA LAPTOP PC4-25600', 'category' => 'Memorias RAM'],
            ['cod' => '7432', 'name' => 'MEMORIA RAM DDR4 SO-DIMM 16GB PC3200 KINGSTON LAPTOP KVR32S22S8/16', 'category' => 'Memorias RAM'],
            ['cod' => '9707', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 8GB PC5600B SAMSUNG LAPTOP M425R1GB4PB0', 'category' => 'Memorias RAM'],
            ['cod' => '9230', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 8GB PC5600 MT LAPTOP 5600MT/S', 'category' => 'Memorias RAM'],
            ['cod' => '7778', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 8GB PC 4800 SAMSUNG LAPTOP BULK', 'category' => 'Memorias RAM'],
            ['cod' => '7976', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 8GB PC5600 ADATA LAPTOP 5600MT/S', 'category' => 'Memorias RAM'],
            ['cod' => '5719', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 8GB PC 4800 KINGSTON LAPTOP KCP548SS6-8', 'category' => 'Memorias RAM'],
            ['cod' => '7537', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 16GB PC4800 ADATA LAPTOP PC5-38400', 'category' => 'Memorias RAM'],
            ['cod' => '9594', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 16GB PC5600 KINGSTON LAPTOP 5600MT/S', 'category' => 'Memorias RAM'],
            ['cod' => '7535', 'name' => 'MEMORIA RAM DDR5 SO-DIMM 32GB PC4800 ADATA LAPTOP PC5-38400', 'category' => 'Memorias RAM'],

            // MESAS
            ['cod' => '8112', 'name' => 'EXTENSION PARA ESCRITORIO EN L ARTECMA REF 1334 DIMENSIONES ANCHO 48.3CM FONDO 38.7CM ALTO 4CM', 'category' => 'Mesas y Escritorios'],
            ['cod' => '4480', 'name' => 'MESA DE VIDRIO TERRAX 1015 TRANSPARENTE 3 NIVELES', 'category' => 'Mesas y Escritorios'],
            ['cod' => '4479', 'name' => 'MESA DE VIDRIO TERRAX 1015 BLACK 3 NIVELES', 'category' => 'Mesas y Escritorios'],

            // MONITORES
            ['cod' => '9604', 'name' => 'MONITOR INFINYTEK 19.5 LED (1920x1080) 75HZ PUERTOS (VGA + HDMI) INCLUYE CABLE HDMI', 'category' => 'Monitores'],
            ['cod' => '3060', 'name' => 'MONITOR VIEWSONIC 18.5 LED VA1903H HDMI VGA', 'category' => 'Monitores'],
            ['cod' => '9781', 'name' => 'MONITOR LG 19.5 LED 1366 x 768 16:09 HDMI VGA PUERTO DE AUDIO INCLUYE CABLE HDMI BLACK', 'category' => 'Monitores'],
            ['cod' => '1363', 'name' => 'MONITOR LG 19.5 LED 1366 x 768 16:09 HDMI VGA BLACK - 20MK400H-B', 'category' => 'Monitores'],
            ['cod' => '3462', 'name' => 'MONITOR HP 19.5 P204V LED 1600 x 900 16:9 HDMI VGA BLACK (3 AÑOS DE GARANTIA)', 'category' => 'Monitores'],
            ['cod' => '10123', 'name' => 'MONITOR LENOVO THINKVISION T2220 21.5 (1920X1080) LED MONITOR BLACK', 'category' => 'Monitores'],
            ['cod' => '9793', 'name' => 'MONITOR LG 23.8 24MS500-B 2X HDMI 1 SALIDA SONIDO 1920x1080 16:9', 'category' => 'Monitores'],
            ['cod' => '10355', 'name' => 'MONITOR ASROCK GAMING CL27FF 27INCH LED IPS FULL HD 1920X1080-100HZ HDMI VGA BLACK', 'category' => 'Monitores'],
            ['cod' => '9252', 'name' => 'MONITOR MSI 27 CURVO FULL HD GAMING 180HZ 1920 X 1080 FHD1MS 2X HDMI 1X DISPLAY PORT', 'category' => 'Monitores'],
            ['cod' => '10376', 'name' => 'MONITOR MSI 27 MAG 276CXF GAMER CURVO FHD (1920X1080) 280HZ 2XHDMI 1XDISPLAYPORT 1 HEADPHONE OUT', 'category' => 'Monitores'],
            ['cod' => '10377', 'name' => 'MONITOR MSI 27 MAG 27CQ6F GAMER CURVO 180HZ/2K 2560 X 1440 2XHDMI 1 DISPLAYPORT', 'category' => 'Monitores'],
            ['cod' => '10120', 'name' => 'MONITOR LG 34 CURVO X34WQ73A-B ULTRAWIDE QHD IPS HDR10 KVM INTEGRADO USB-C BLACK', 'category' => 'Monitores'],
            ['cod' => '6309', 'name' => 'SOPORTE PARA MONITOR LCD/LED KLIPX KPM310 DOBLE MONITOR 13 32', 'category' => 'Monitores'],
            ['cod' => '514', 'name' => 'ADAPTADOR PARA MONITOR LG 19V 1.3AMP', 'category' => 'Monitores'],
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

        $this->command->info("✅ Segunda mitad (Parte 1) - Productos creados: {$created}");
    }
}
