CREATE TABLE equipamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,equipamentos
    nome VARCHAR(100) NOT NULL,
    marca VARCHAR(100),
    modelo VARCHAR(100),
    serial VARCHAR(20),
    estado VARCHAR(50),
    local_equipamento VARCHAR(20),
    data_aquisicao DATE,
    tipo_aquisicao VARCHAR(50),
    custo_aquisicao DECIMAL(10,2),
    ano_fabrico YEAR,
    grupo VARCHAR(100),
    departamento VARCHAR(100),
    imagem VARCHAR(255)
);

INSERT INTO equipamentos (nome, marca, modelo, serial, estado, local_equipamento, data_aquisicao, tipo_aquisicao, custo_aquisicao, ano_fabrico, grupo, departamento, imagem) VALUES
('Desfibrilhador',          'Zoll',             'R Series',         '02-3567-00', 'Disponivel',       'WA.03.324', '2021-03-15', 'Compra',     12500.00,  2020, 'Grupo 2- Suporte de Vida',  'ICU',        '../assets/images/Placeholder.jpg'),
('Ventilador',              'Dräger',            'Evita V300',       '03-1234-01', 'Em Uso',           'WB.02.201', '2020-07-22', 'Compra',     35000.00,  2019, 'Grupo 2- Suporte de Vida',  'ICU',        '../assets/images/Placeholder.jpg'),
('Monitor de Sinais Vitais','Philips',           'IntelliVue MX450', '04-2891-02', 'Disponivel',       'WA.01.101', '2022-01-10', 'Leasing',    18000.00,  2021, 'Grupo 1- Monitorizacao',    'Cardiology', '../assets/images/Placeholder.jpg'),
('Bomba Infusora',          'B.Braun',           'Infusomat Space',  '05-4421-03', 'Em Uso',           'WC.04.412', '2021-11-05', 'Compra',      4200.00,  2021, 'Grupo 3- Terapia',          'ICU',        '../assets/images/Placeholder.jpg'),
('Ecógrafo',                'GE Healthcare',     'LOGIQ E10',        '06-7823-04', 'Em Manutenção',    'WB.03.305', '2019-06-18', 'Compra',     85000.00,  2018, 'Grupo 4- Diagnostico',      'Radiology',  '../assets/images/Placeholder.jpg'),
('Eletrocardiógrafo',       'Mortara',           'ELI 380',          '07-1145-05', 'Disponivel',       'WA.02.214', '2022-09-30', 'Compra',      6800.00,  2022, 'Grupo 1- Monitorizacao',    'Cardiology', '../assets/images/Placeholder.jpg'),
('Ressonância Magnética',   'Siemens',           'MAGNETOM Vida',    '08-9934-06', 'Em Uso',           'WD.01.110', '2018-04-12', 'Leasing',   950000.00, 2017, 'Grupo 4- Diagnostico',      'Radiology',  '../assets/images/Placeholder.jpg'),
('Tomógrafo',               'Canon Medical',     'Aquilion ONE',     '09-6612-07', 'Disponivel',       'WD.02.220', '2020-02-28', 'Compra',    620000.00, 2019, 'Grupo 4- Diagnostico',      'Radiology',  '../assets/images/Placeholder.jpg'),
('Autoclave',               'Tuttnauer',         '3870EA',           '10-3345-08', 'Disponivel',       'WC.01.105', '2021-08-14', 'Compra',     15000.00,  2021, 'Grupo 6- Esterilizacao',    'Surgery',    '../assets/images/Placeholder.jpg'),
('Oxímetro',                'Nonin',             'Model 9560',       '11-2278-09', 'Fora de Servico',  'WA.04.401', '2019-12-01', 'Compra',       850.00,  2019, 'Grupo 1- Monitorizacao',    'ICU',        '../assets/images/Placeholder.jpg'),
('Bisturi Elétrico',        'Valleylab',         'FT10',             '12-5531-10', 'Disponivel',       'WC.03.318', '2022-05-20', 'Compra',      9500.00,  2022, 'Grupo 3- Terapia',          'Surgery',    '../assets/images/Placeholder.jpg'),
('Incubadora',              'Dräger',            'Isolette C2',      '13-8892-11', 'Em Uso',           'WB.04.420', '2020-10-07', 'Compra',     28000.00,  2020, 'Grupo 2- Suporte de Vida',  'ICU',        '../assets/images/Placeholder.jpg'),
('Analisador de Gases',     'Radiometer',        'ABL90 FLEX',       '14-4467-12', 'Disponivel',       'WA.03.312', '2021-06-25', 'Leasing',    42000.00,  2021, 'Grupo 5- Laboratorio',      'ICU',        '../assets/images/Placeholder.jpg'),
('Laringoscópio',           'Karl Storz',        'C-MAC D-BLADE',    '15-7723-13', 'Disponivel',       'WC.02.208', '2022-03-11', 'Compra',      3200.00,  2022, 'Grupo 3- Terapia',          'Surgery',    '../assets/images/Placeholder.jpg'),
('Centrifugadora',          'Eppendorf',         '5810R',            '16-1189-14', 'Em Manutenção',    'WE.01.115', '2019-09-03', 'Compra',      8700.00,  2019, 'Grupo 5- Laboratorio',      'Neurology',  '../assets/images/Placeholder.jpg'),
('Cama Articulada',         'Stryker',           'InTouch L&D',      '17-6634-15', 'Em Uso',           'WB.01.102', '2021-01-17', 'Leasing',     7500.00,  2020, 'Grupo 7- Reabilitacao',     'Neurology',  '../assets/images/Placeholder.jpg'),
('Neuroestimulador',        'Medtronic',         'Activa PC',        '18-3312-16', 'Disponivel',       'WD.03.330', '2020-11-29', 'Compra',     55000.00,  2020, 'Grupo 3- Terapia',          'Neurology',  '../assets/images/Placeholder.jpg'),
('Electroencefalógrafo',    'Nihon Kohden',      'EEG-1200',         '19-8845-17', 'Disponivel',       'WD.04.440', '2022-07-08', 'Compra',     32000.00,  2022, 'Grupo 1- Monitorizacao',    'Neurology',  '../assets/images/Placeholder.jpg'),
('Hemodialisador',          'Fresenius',         '5008S',            '20-5567-18', 'Em Uso',           'WC.05.501', '2019-05-14', 'Leasing',    75000.00,  2018, 'Grupo 2- Suporte de Vida',  'ICU',        '../assets/images/Placeholder.jpg'),
('Microscópio Cirúrgico',   'Zeiss',             'KINEVO 900',       '21-9901-19', 'Disponivel',       'WC.04.410', '2021-04-22', 'Compra',    180000.00,  2021, 'Grupo 4- Diagnostico',      'Surgery',    '../assets/images/Placeholder.jpg');