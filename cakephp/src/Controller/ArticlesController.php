<?php
namespace App\Controller;

class ArticlesController extends AppController
{
  public function initialize() : void
  {
    parent::initialize();
    
    $this->loadComponent('Paginator');
    /**
     * Flash component
     * https://book.cakephp.org/4/en/controllers/components/flash.html
     */
    $this->loadComponent('Flash');  // include Flash component
  }

  public function index()
  {
    $this->Authorization->skipAuthorization();
    $articles = $this->Paginator->paginate($this->Articles->find());
    $this->set(compact('articles'));
  }

  public function view($slug = null)
  {
    $this->Authorization->skipAuthorization();
    /**
     * Dynamic Finder (findBySlug)
     * https://book.cakephp.org/4/en/orm/retrieving-data-and-resultsets.html#dynamic-finders
     * 
     * firstOrFail() will throw NotFoundException() if no record is found.
     */
    $article = $this
      ->Articles
      ->findBySlug($slug)
      ->contain('Tags')
      ->firstOrFail();
    $this->set(compact('article'));
  }

  public function add()
  {
    $article = $this->Articles->newEmptyEntity();
    $this->Authorization->authorize($article);
    if ($this->request->is('post'))
    {
      $article = $this->Articles->patchEntity($article, $this->request->getData());

      // Hardcoding the user_id is temporary, and will be removed later
      // when we build authentication out.
      // $article->user_id = 5;
      $article->user_id = $this->request->getAttribute('identity')->getIdentifier();

      if ($this->Articles->save($article))
      {
        $this->Flash->success(__('Your article has been saved.'));
        return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Unable to add your article.'));
    }
    // Get a list of tags.
    $tags = $this->Articles->Tags->find('list')->all();

    // Set tags to the view context
    $this->set('tags', $tags);
    $this->set('article', $article);
  }

  public function edit($slug = null)
  {
    $article = $this
      ->Articles
      ->findBySlug($slug)
      ->contain('Tags')
      ->firstOrFail();
    $this->Authorization->authorize($article);

    if ($this->request->is(['post', 'put']))
    {
      // change accessible fields
      // https://book.cakephp.org/4/en/orm/saving-data.html#changing-accessible-fields
      $this->Articles->patchEntity($article, $this->request->getData(), [
        // Added: Disable modification of user_id.
        'accessibleFields' => ['user_id' => false]
      ]);
      if ($this->Articles->save($article))
      {
          $this->Flash->success(__('Your article has been updated.'));
          return $this->redirect(['action' => 'index']);
      }
      $this->Flash->error(__('Unable to update your article.'));
    }
    // Get a list of tags.
    $tags = $this->Articles->Tags->find('list')->all();

    // Set tags to the view context
    $this->set('tags', $tags);
    $this->set('article', $article);
  }

  public function delete($slug = null)
  {
    $this->request->allowMethod(['post', 'delete']);
    $article = $this->Articles->findBySlug($slug)->firstOrFail();
    $this->Authorization->authorize($article);
    if ($this->Articles->delete($article))
    {
      $this->Flash->success(__('The {0} article has been deleted.', $article->title));
      return $this->redirect(['action' => 'index']);
    }
  }

  // Since passed arguments are passed as method parameters, you could also write the action using PHP’s variadic argument:
  public function tags(...$tags)
  {
    $this->Authorization->skipAuthorization();
    // The 'pass' key is provided by CakePHP and contains all
    // the passed URL path segments in the request.
    // https://book.cakephp.org/4/en/controllers/request-response.html#cake-request
    $tags = $this->request->getParam('pass');

    $articles = $this->Articles->find('tagged', [
      'tags' => $tags
    ])->all();

    // Pass variables into the view template context.
    $this->set([
        'articles' => $articles,
        'tags' => $tags
    ]);
  }
}