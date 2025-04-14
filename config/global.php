<?php

return [
    'db_to_display' => [
        'user' => ['Receptor', 'ScRNA', 'Individual', 'Sample', 'T interaction', 'B interaction', 'CyTOF'],
        'guest' => ['Receptor', 'Individual', 'Sample', 'T interaction', 'B interaction', 'CyTOF'],
    ],

    'tools' => [
        [
            'name' => 'pMTnet',
            // {{ asset('img/tools/pmtnet.png') }}
            'img' => 'img/tools/pmtnet.png',
            'url' => [
                'name' => 'Online Analysis',
                'link' => 'https://dbai.biohpc.swmed.edu/pmtnet/',
            ],
            'git_url' => 'https://github.com/tianshilu/pMTnet',
            'description' => 'pMTnet is a deep learning neural network-based prediction of TCR\'s binding specificity toward peptide-MHC complex.'
        ],
        [
            'name' => 'BepiTBR',
            'img' => 'img/tools/bepiTBR.png',
            'url' => [
                'name' => 'Online Analysis',
                'link' => 'https://dbai.biohpc.swmed.edu/bepitbr/',
            ],
            'git_url' => 'https://github.com/zzhu33/BepiTBR',
            'description' => 'BepiTBR is a B cell epitope prediction model that demonstrates improved performance by incorporating prediction of nearby CD4+ T cell epitopes close to the B cell epitopes.'
        ],
        [
            'name' => 'Cytomulate',
            'img' => 'img/tools/cytof.png',
            'url' => [
                'name' => 'Documentation',
                'link' => 'https://cytomulate.readthedocs.io/en/dev/',
            ],
            'git_url' => 'https://github.com/kevin931/cytomulate/blob/dev/docs/source/index.rst',
            'description' => 'Cytomulate is a package to simulation realistic data for Mass Cytometry or Cytometry by Time-of-Flight (CyTOF). We strive to achieve both model-based and real-data-based simulation as solutions to benchmarking, method validation, prototyping, and more.'
        ],
        [
            'name' => 'CyTOF Playground',
            'img' => 'img/tools/cytof-playground.png',
            'url' => [
                'name' => 'Web Portal',
                'link' => 'https://dbai.biohpc.swmed.edu/cytof-dr-playground/',
            ],
            'description' => 'CyTOF playground is an online web tool for displaying the results of benchmarking the dimension reduction algorithms on CyTOF data. There are two main features in this web tool. The first one is to search for DR results based on multiple input conditions (left column). And the other is to dynamically calculate the average of the performance scores for the user-chosen metrics and to rank the chosen DR methods based on the average scores (top row).'
        ],
        [
            'name' => 'CyTOF Package',
            'img' => 'img/tools/cytof-package.png',
            'url' => [
                'name' => 'Documentation',
                'link' => 'https://cytofdr.readthedocs.io/en/stable/',
            ],
            'description' => 'CyTOF Package offers our unified implementation pipeline for homogenized input/output of the dimension reduction methods and the benchmark metrics for assessing their accuracy.'
        ],
        [
            'name' => 'Scina',
            'img' => 'img/tools/scina.png',
            'url' => [
                'name' => 'Online Analysis',
                'link' => 'https://dbai.biohpc.swmed.edu/scina/',
            ],
            'git_url' => 'https://github.com/jcao89757/SCINA',
            'description' => 'An automatic cell type detection and assignment algorithm for single cell RNA-Seq (scRNA-seq) and Cytof/FACS data. SCINA is capable of assigning cell type identities to a pool of cells profiled by scRNA-Seq or Cytof/FACS data with prior knowledge of signatures, such as genes and protein symbols that are highly or lowly expressed in each cell type.'
        ],
        [
            'name' => 'Tessa',
            'img' => 'img/tools/tessa.png',
            'git_url' => 'https://github.com/jcao89757/TESSA',
            'description' => 'Tessa is a Bayesian model to integrate T cell receptor (TCR) sequence profiling with transcriptomes of T cells. Enabled by the recently developed single cell sequencing techniques, which provide both TCR sequences and RNA sequences of each T cell concurrently, Tessa maps the functional landscape of the TCR repertoire, and generates insights into understanding human immune response to diseases. As the first part of tessa, BriseisEncoder is employed prior to the Bayesian algorithm to capture the TCR sequence features and create numerical embeddings. Please refer to our paper for more details: \'Mapping the Functional Landscape of TCR Repertoire.\',Zhang, Z., Xiong, D., Wang, X. et al. 2021.'
        ],
        [
            'name' => 'Spacia',
            'img' => 'img/tools/spacia.png',
            'url' => [
                'name' => 'Documentation',
                'link' => 'https://spacia-doc.readthedocs.io/en/latest/#',
            ],
            'description' => 'Emerging spatially resolved transcriptomics (SRT) techniques provide rich information on gene expression and cell locations that enable accurate and single cell-resolution detection of cell-2-cell communications (CCCs) in their spatial context. We developed a Bayesian multi-instance learning method, named spacia, to detect CCCs from SRT data.'
        ],
    ]
]

?>