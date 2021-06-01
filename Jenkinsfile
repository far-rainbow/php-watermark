pipeline {
  agent any
  stages {
    stage('BUILD') {
      steps {
        sh 'make build'
      }
    }

    stage('BENCH') {
      steps {
        sh 'make bench'
      }
    }

    stage('PUSH') {
      steps {
        sh 'make push'
      }
    }

  }
  environment {
    REGISTRY = 'hub_registry'
    IMAGE_TAG = 'master'
  }
}