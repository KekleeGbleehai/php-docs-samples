<?php
/**
 * Copyright 2018 Google LLC.
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
 * @see https://github.com/GoogleCloudPlatform/php-docs-samples/tree/master/bigquery/api/README.md
 */

namespace Google\Cloud\Samples\BigQuery;

# [START bigquery_load_table_gcs_json]
use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\Core\ExponentialBackoff;

/**
 * Import data from storage json.
 *
 * @param string $projectId The project Id of your Google Cloud Project.
 * @param string $datasetId The BigQuery dataset ID.
 * @param string $tableId The BigQuery table ID.
 */
function import_from_storage_json(
    string $projectId,
    string $datasetId,
    string $tableId = 'us_states'
): void {
    // instantiate the bigquery table service
    $bigQuery = new BigQueryClient([
      'projectId' => $projectId,
    ]);
    $dataset = $bigQuery->dataset($datasetId);
    $table = $dataset->table($tableId);

    // create the import job
    $gcsUri = 'gs://cloud-samples-data/bigquery/us-states/us-states.json';
    $schema = [
      'fields' => [
        ['name' => 'name', 'type' => 'string'],
        ['name' => 'post_abbr', 'type' => 'string']
        ]
      ];
    $loadConfig = $table->loadFromStorage($gcsUri)->schema($schema)->sourceFormat('NEWLINE_DELIMITED_JSON');
    $job = $table->runJob($loadConfig);
    // poll the job until it is complete
    $backoff = new ExponentialBackoff(10);
    $backoff->execute(function () use ($job) {
        print('Waiting for job to complete' . PHP_EOL);
        $job->reload();
        if (!$job->isComplete()) {
            throw new \Exception('Job has not yet completed', 500);
        }
    });
    // check if the job has errors
    if (isset($job->info()['status']['errorResult'])) {
        $error = $job->info()['status']['errorResult']['message'];
        printf('Error running job: %s' . PHP_EOL, $error);
    } else {
        print('Data imported successfully' . PHP_EOL);
    }
}
# [END bigquery_load_table_gcs_json]
require_once __DIR__ . '/../../../testing/sample_helpers.php';
\Google\Cloud\Samples\execute_sample(__FILE__, __NAMESPACE__, $argv);
