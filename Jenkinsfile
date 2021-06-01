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

  }
  environment {
    REGISTRY = 'hub_registry'
    IMAGE_TAG = 'master'
  }
}