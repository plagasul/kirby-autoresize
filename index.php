<?php
Kirby::plugin('medienbaecker/autoresize', [
   'options' => [
        'sizes' => [
            'maxWidth'    => 1600,
            'maxHeight'   => 1600,
            'longestSide' => null,
          ]
    ],
    
    'hooks' => [
        'file.create:after' => function ($file) {
          
            $option = option('medienbaecker.autoresize.sizes');
            $mW = $option['maxWidth'];
            $mH = $option['maxHeight'];
            $ls = $option['longestSide'];
            
            $oldW = $file->width();
            $oldH = $file->height();
            
            if(!empty($ls)){
              $maxW = $ls;
              $maxH = $ls;
            } else {
              $maxW = $mW;
              $maxH = $mH;
            };
          
          
            if($file->isResizable()) {
              if($oldW > $maxW || $oldH > $maxH){
                    try {
                        kirby()->thumb($file->root(), $file->root(), [
                            'width'  => $maxW,
                            'height' => $maxH,
                        ]);
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }
                 }
            }
        },
        'file.replace:after' => function ($newFile, $oldFile) {
            kirby()->trigger('file.create:after', $newFile);
        }
    ]
]);
