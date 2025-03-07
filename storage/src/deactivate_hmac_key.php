<?php
/**
 * Copyright 2019 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * For instructions on how to run the full sample:
 *
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/storage/README.md
 */

namespace Google\Cloud\Samples\Storage;

# [START storage_deactivate_hmac_key]
use Google\Cloud\Storage\StorageClient;

/**
 * Deactivate an HMAC key.
 *
 * @param string $projectId The ID of your Google Cloud Platform project.
 *        (e.g. 'my-project-id')
 * @param string $accessId Access ID for an inactive HMAC key.
 *        (e.g. 'GOOG0234230X00')
 */
function deactivate_hmac_key(string $projectId, string $accessId): void
{
    $storage = new StorageClient();
    // By default hmacKey will use the projectId used by StorageClient().
    $hmacKey = $storage->hmacKey($accessId, $projectId);

    $hmacKey->update('INACTIVE');

    print('The HMAC key is now inactive.' . PHP_EOL);
    printf('HMAC key Metadata: %s' . PHP_EOL, print_r($hmacKey->info(), true));
}
# [END storage_deactivate_hmac_key]

// The following 2 lines are only needed to run the samples
require_once __DIR__ . '/../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
