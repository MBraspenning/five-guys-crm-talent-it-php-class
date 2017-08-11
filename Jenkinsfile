pipeline {
    agent any

    stages {
        stage('Check out SCM') {
            steps {
               checkout scm 
            }
        }
        stage('Composer setup') {
            steps {
               sh 'curl -sS https://getcomposer.org/installer|php -'
               sh 'php composer.phar install'
            }
        }
        stage('Vulnerability Checks (CVE)') {
            steps {
                sh 'if [ ! -f security-checker.phar ]; then curl -O http://get.sensiolabs.org/security-checker.phar; fi;'
                sh 'php security-checker security:check composer.lock'
            }
        }
        stage('Run unit tests') {
            steps {
                sh './vendor/bin/phpunit -v'
            }
        }
        stage('Do static analysis with phing') {
            steps {
                echo 'phing'
            }
        }
        stage('Create deployment package') {
            steps {
                echo 'tar -cjf /tmp/application-latest.tar.bz2 .'
            }
        }
        stage('Deploy package on enfironment') {
            steps {
                echo 'Running phing...'
            }
        }
        stage('Migrate DB') {
            steps {
                echo 'Running DBDeploy...'
            }
        }
        stage('Warm-up caches') {
            steps {
                echo 'Running DBDeploy...'
            }
        }
        stage('Run release quality tests') {
            steps {
                echo 'Integration tests...'
                echo 'Acceptance tests...'
                echo 'End-to-end tests...'
                echo 'Regression tests...'
                echo 'Performance tests...'
                echo 'Security tests...'
                echo 'Resilience tests...'
                echo 'Accessibility tests...'
                echo 'UX tests...'
            }
        }
        stage('Stop crons, workers and daemons') {
            steps {
                echo 'Stopping crons...'
            }
        }
        stage('Phase out old with new') {
            steps {
                echo 'Put new system in LB...'
                echo 'Redirect portion of traffic to new node'
                echo 'Validate behaviour of new node'
                echo 'Take old system out LB...'
                echo 'Validate release...'
            }
        }
        stage('Start crons, workers and daemons') {
            steps {
                echo 'Starting crons...'
            }
        }
        stage('Release Reporting') {
            steps {
                echo 'Reporting about this release'
            }
        }
    }
}
