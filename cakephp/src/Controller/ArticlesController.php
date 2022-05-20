<?php
namespace App\Controller;

class ArticlesController extends AppController
{
  public function initialize() : void
  {
    $this->loadComponent('Paginator');
    /**
     * Flash component
     * https://book.cakephp.org/4/en/controllers/components/flash.html
     */
    $this->loadComponent('Flash');  // include Flash component
  }

  public function index()
  {
    $articles = $this->Paginator->paginate($this->Articles->find());
    $this->set(compact('articles'));
  }

  public function view($slug = null)
  {
    /**
     * Dynamic Finder (findBySlug)
     * https://book.cakephp.org/4/en/orm/retrieving-data-and-resultsets.html#dynamic-finders
     * 
     * firstOrFail() will throw NotFoundException() if no record is found.
     */
    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    $this->set(compact('article'));
  }

  public function add()
  {
    $article = $this->Articles->newEmptyEntity();
    if ($this->request->is('post'))
    {
      $article = $this->Articles->patchEntity($article, $this->request->getData());

      // Hardcoding the user_id is temporary, and will be removed later
      // when we build authentication out.
      $article->user_id = 5;

      if ($this->Articles->save($article))
      {
        $this->Flash->success(__('Your article has been saved.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Unable to add your article.'));
    }
    $this->set('article', $article);
  }

  public function edit($slug = null)
  {
    $article = $this->Articles->findBySlug($slug)->firstOrFail();

    if ($this->request->is(['post', 'put']))
    {
      $this->Articles->patchEntity($article, $this->request->getData());
      if ($this->Articles->save($article))
      {
          $this->Flash->success(__('Your article has been updated.'));
          return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Unable to update your article.'));
    }
    $this->set('article', $article);
  }

  public function delete($slug = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    if ($this->Articles->delete($article))
    {
      $this->Flash->success(__('The {0} article has been deleted.', $article->title));
      return $this->redirect(['action' => 'index']);
    }
  }
}