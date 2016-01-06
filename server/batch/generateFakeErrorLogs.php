<?php

if (!isset($argv[1]) || $argv[1] != '--force') {
    die("********WARNING: THIS SCRIPT SHOULD NOT BE EXECUTED ON PRODUCTION********\nPlease specify --force to run.\n");
}

$appIds = [1, 2, 3];

$environments = [
    '' => [
        'os' => [
            'name' => 'android',
            'version' => 4
        ],
        'app' => ['version' => 3],
        'device' => ['name' => 'Samsung Galaxy S4']
    ],
    'user1' => [
        'os' => [
            'name' => 'android',
            'version' => 3
        ],
        'app' => ['version' => 1],
        'device' => ['name' => 'Samsung Galaxy S']
    ],
    'user2' => [
        'os' => [
            'name' => 'android',
            'version' => 3
        ],
        'app' => ['version' => 1],
        'device' => ['name' => 'Samsung Galaxy S2']
    ],
    'user3' => [
        'os' => [
            'name' => 'android',
            'version' => 4
        ],
        'app' => ['version' => 2],
        'device' => ['name' => 'Moto G']
    ],
    'user4' => [
        'os' => [
            'name' => 'android',
            'version' => 5
        ],
        'app' => ['version' => 2],
        'device' => ['name' => 'Moto G2']
    ],
    'user5' => [
        'os' => [
            'name' => 'android',
            'version' => 5
        ],
        'app' => ['version' => 3],
        'device' => ['name' => 'Nexus 4']
    ],
    'user6' => [
        'os' => [
            'name' => 'android',
            'version' => 5
        ],
        'app' => ['version' => 3],
        'device' => ['name' => 'Nexus 5']
    ],
    'user7' => [
        'os' => [
            'name' => 'android',
            'version' => 5
        ],
        'app' => ['version' => 3],
        'device' => ['name' => 'Nexus 6']
    ],
    'user8' => [
        'os' => [
            'name' => 'android',
            'version' => 5
        ],
        'app' => ['version' => 3],
        'device' => ['name' => 'Xiaomi Mi4']
    ],
    'user9' => [
        'os' => [
            'name' => 'android',
            'version' => 6
        ],
        'app' => ['version' => 3],
        'device' => ['name' => 'Xiaomi Mi4i']
    ],
    'iuser1' => [
        'os' => [
            'name' => 'ios',
            'version' => 7
        ],
        'app' => ['version' => 5],
        'device' => ['name' => 'iPhone 5']
    ],
    'iuser2' => [
        'os' => [
            'name' => 'ios',
            'version' => 7
        ],
        'app' => ['version' => 6],
        'device' => ['name' => 'iPhone 5']
    ],
    'iuser3' => [
        'os' => [
            'name' => 'ios',
            'version' => 8
        ],
        'app' => ['version' => 7],
        'device' => ['name' => 'iPhone 6']
    ]
];

$tags = ['srp', 'jd', 'homepage', 'mnjhome', 'login', 'logout', 'jd2', 'ads', 'bms'];
$execptionTypes =['MathException', 'RuntimeException', 'JavaException', 'ActivityException', 'NullPointerException', 'FileNotFoundException', 'IOException', 'SaveException', 'GetException', 'EditException', 'UserException', 'ScreenException', 'UncaughtException', 'FatalException', 'MethodUndefinedException', 'ClassNotFoundException', 'ConversionException', 'CodeException', 'OutOfMemoryException', 'StackOverflowException', 'HeapException', 'GarbageCollectorException'];

$uIds = array_keys($environments);
for ($i=0; $i<10000; ++$i) {
    $timestamp1 = time() - rand(0, 86400*30);
    $timestamp2 = time() - rand(0, 86400*30);
    $count1 = rand(1, 3);
    $count2 = rand(1, 3);
    $appId = $appIds[rand(0, count($appIds)-1)];
    $uIdIndex = $uId = $uIds[rand(0, count($uIds)-1)];
    if (empty($uId)) {
        $uIdIndex = $uIds[rand(0, count($uIds)-1)];
    }
    $osName = $environments[$uIdIndex]['os']['name'];
    $osVersion = $environments[$uIdIndex]['os']['version'];
    $appVersion = $environments[$uIdIndex]['app']['version'];
    $deviceName = $environments[$uIdIndex]['device']['name'];
    $tag1 = $tags[rand(0, count($tags)-1)];
    $tag2 = $tags[rand(0, count($tags)-1)];
    $execptionType1 = $execptionTypes[rand(0, count($execptionTypes)-1)];
    $execptionType2 = $execptionTypes[rand(0, count($execptionTypes)-1)];

    $errLogUrl = "http://127.0.0.1/nLogger/errLogger.php?errorLogs={%22source%22:%22app%22,%22appId%22:%22$appId%22,".(empty($uId) ? "" : "%22uId%22:%22$uId%22,")."%22environment%22:{%22os%22:{%22name%22:%22$osName%22,%22version%22:%22$osVersion%22},%22app%22:{%22version%22:%22$appVersion%22},%22device%22:{%22name%22:%22$deviceName%22}},%22exceptions%22:[{%22tag%22:%22$tag1%22,%22count%22:$count2,%22timestamp%22:$timestamp1,%22type%22:%22$execptionType1%22,%22message%22:%22Division%20by%20zero%20error%22,%22code%22:5,%22file%22:%22Example.java%22,%22line%22:23,%22stackTrace%22:%22java.lang.$execptionType1%5Cn%5Ctat+org.json.JSONTokener.nextCleanInternal%28JSONTokener.java%3A116%29%5Cn%5Ctat+org.json.JSONTokener.nextValue%28JSONTokener.java%3A94%29%5Cn%5Ctat+org.json.JSONObject.%3Cinit%3E%28JSONObject.java%3A154%29%5Cn%5Ctat+org.json.JSONObject.%3Cinit%3E%28JSONObject.java%3A171%29%5Cn%5Ctat+com.naukriGulf.app.gcm.a.a%28Unknown+Source%29%5Cn%5Ctat+com.naukriGulf.app.GCMIntentService.a%28Unknown+Source%29%5Cn%5Ctat+com.google.android.gcm.a.onHandleIntent%28Unknown+Source%29%5Cn%5Ctat+android.app.IntentService%24ServiceHandler.handleMessage%28IntentService.java%3A65%29%5Cn%5Ctat+android.os.Handler.dispatchMessage%28Handler.java%3A99%29%5Cn%5Ctat+android.os.Looper.loop%28Looper.java%3A137%29%5Cn%5Ctat+android.os.HandlerThread.run%28HandlerThread.java%3A60%29%22},{%22tag%22:%22$tag2%22,%22count%22:$count2,%22timestamp%22:$timestamp2,%22type%22:%22$execptionType2%22,%22message%22:%22Division%20by%20zero%20error%22,%22code%22:5,%22file%22:%22Example.java%22,%22line%22:23,%22stackTrace%22:%22java.io.$execptionType2%5Cn%5Ctat+org.json.JSONTokener.nextCleanInternal%28JSONTokener.java%3A116%29%5Cn%5Ctat+org.json.JSONTokener.nextValue%28JSONTokener.java%3A94%29%5Cn%5Ctat+org.json.JSONObject.%3Cinit%3E%28JSONObject.java%3A154%29%5Cn%5Ctat+org.json.JSONObject.%3Cinit%3E%28JSONObject.java%3A171%29%5Cn%5Ctat+com.naukriGulf.app.gcm.a.a%28Unknown+Source%29%5Cn%5Ctat+com.naukriGulf.app.GCMIntentService.a%28Unknown+Source%29%5Cn%5Ctat+com.google.android.gcm.a.onHandleIntent%28Unknown+Source%29%5Cn%5Ctat+android.app.IntentService%24ServiceHandler.handleMessage%28IntentService.java%3A65%29%5Cn%5Ctat+android.os.Handler.dispatchMessage%28Handler.java%3A99%29%5Cn%5Ctat+android.os.Looper.loop%28Looper.java%3A137%29%5Cn%5Ctat+android.os.HandlerThread.run%28HandlerThread.java%3A60%29%22}]}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $errLogUrl);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
}
echo "Done\n";
