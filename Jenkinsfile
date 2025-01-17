pipeline {
    agent any

    triggers {
        githubPush()
    }

    stages {
        stage('Cloner le dépôt') {
            steps {
                withCredentials([string(credentialsId: 'github-pat', variable: 'GITHUB_TOKEN')]) {
                    git branch: 'main', url: "https://${GITHUB_TOKEN}@github.com/thecabs/blogappbackend.git"
                }
            }
        }

        stage('Mettre à jour le fichier .env') {
    steps {
        script {
            sh '''
            if [ ! -f .env ]; then
                cp .env.example .env
            fi
            
            # Remplacer les valeurs dans .env
            sed -i "s|DB_HOST=.*|DB_HOST=db|g" .env
            sed -i "s|DB_PORT=.*|DB_PORT=3306|g" .env
            sed -i "s|DB_DATABASE=.*|DB_DATABASE=laravel|g" .env
            sed -i "s|DB_USERNAME=.*|DB_USERNAME=laravel|g" .env
            sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=laravel|g" .env
            '''
        }
    }
}



        stage('Installer les dépendances') {
            steps {
                sh 'composer install'
                sh 'php artisan key:generate'
            }
        }

        stage('Tests unitaires') {
            steps {
                script {
                    def startTime = System.currentTimeMillis()
                    sh 'php artisan test tests/Unit --log-junit build/unit-test-results.xml'
                    def endTime = System.currentTimeMillis()
                    env.UNIT_TEST_DURATION = ((endTime - startTime) / 1000).toString()
                }
            }
        }

        stage('Tests d\'intégration avec Docker Compose') {
    steps {
        script {
            def startTime = System.currentTimeMillis()
            sh '''
                # Nettoyer les conteneurs existants
                docker-compose down || true

                # Supprimer tout conteneur résiduel en conflit
                docker rm -f laravel_app_new || true
                docker rm -f laravel_db_new || true

                # Construire les images
                docker-compose build

                # Démarrer les services
                docker-compose up -d

                # Attendre que la base de données soit prête
                docker exec laravel_app_new bash -c 'for i in {1..30}; do mysqladmin ping -h db --silent && break || sleep 1; done'

                # Exécuter les migrations et les tests
                docker exec laravel_app_new php artisan migrate --force
                docker exec laravel_app_new php artisan test tests/Integration --log-junit /var/www/html/build/integration-test-results.xml

                # Arrêter les services
                docker-compose stop
            '''
            def endTime = System.currentTimeMillis()
            env.INTEGRATION_TEST_DURATION = ((endTime - startTime) / 1000).toString()
        }
    }
}



        stage('Générer le rapport consolidé') {
            steps {
                script {
                    sh """
                        php artisan test:report \
                            build/unit-test-results.xml \
                            build/integration-test-results.xml \
                            build/test-report.pdf \
                            --unit-time=${env.UNIT_TEST_DURATION} \
                            --integration-time=${env.INTEGRATION_TEST_DURATION}
                    """
                    archiveArtifacts artifacts: 'build/test-report.pdf', allowEmptyArchive: false
                }
            }
        }

        stage('Construire l\'image Docker') {
            steps {
                sh 'docker build -t laravel_app_new:latest .'
            }
        }

        stage('Démarrer les services Docker') {
            steps {
                sh 'docker-compose up -d'
            }
        }

        stage('Vérifier l\'image Docker') {
            steps {
                script {
                    def images = sh(script: 'docker images -q laravel_app_new:latest', returnStdout: true).trim()
                    if (!images) {
                        error "Image 'laravel_app_new:latest' non trouvée. Assurez-vous qu'elle est construite avant de la taguer."
                    }
                }
            }
        }

        stage('Taguer et pousser l\'image Docker') {
            steps {
               withCredentials([usernamePassword(credentialsId: 'dockerhub-credentials', usernameVariable: 'DOCKER_USERNAME', passwordVariable: 'DOCKER_PASSWORD')]) {
                    sh '''
                        echo $DOCKER_PASSWORD | docker login -u $DOCKER_USERNAME --password-stdin
                        docker tag laravel_app_new:latest $DOCKER_USERNAME/laravel_app_new:latest
                        docker push $DOCKER_USERNAME/laravel_app_new:latest
                    '''
                }
            }
         }

        stage('Déployer l\'application') {
            when {
                expression { currentBuild.result == null || currentBuild.result == 'SUCCESS' }
            }
            steps {
                sh '''
                    docker-compose down || true
                    docker-compose -f docker-compose.prod.yml up -d
                '''
            }
        }

       
    }

    post {
        always {
            archiveArtifacts artifacts: 'build/*.xml', allowEmptyArchive: true
        }

        success {
            emailext(
                to: 'jaurescabreldongmo@gmail.com',
                subject: "Build SUCCESS: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: "Le build ${env.JOB_NAME} #${env.BUILD_NUMBER} a réussi.\nDétails : ${env.BUILD_URL}"
            )
        }

        failure {
            emailext(
                to: 'jaurescabreldongmo@gmail.com',
                subject: "Build FAILED: ${env.JOB_NAME} #${env.BUILD_NUMBER}",
                body: "Le build ${env.JOB_NAME} #${env.BUILD_NUMBER} a échoué.\nDétails : ${env.BUILD_URL}"
            )
        }
    }
}
